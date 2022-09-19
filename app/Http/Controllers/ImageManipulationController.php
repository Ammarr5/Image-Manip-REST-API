<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompressImageRequest;
use App\Http\Requests\GreyscaleImageRequest;
use App\Http\Requests\PixelateImageRequest;
use App\Http\Requests\ResizeImageRequest;
use App\Models\ImageManipulation;
use App\Http\Resources\ImageManipulationResource;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;

class ImageManipulationController extends Controller
{

    const TYPE_RESIZE = "resize";
    const TYPE_PIXELATE = "pixelate";
    const TYPE_GREYSCALE = "greyscale";
    const TYPE_COMPRESS = "compress";

    /**
     * Display a listing of all images created by current user
     *
     * @return App\Http\Resources\ImageManipulationResource;
     */
    public function index()
    {
        $result = Gate::allows("viewAny") ? ImageManipulationResource::collection(ImageManipulation::all()) : ImageManipulationResource::collection(ImageManipulation::all()->where("user_id", auth()->user()->getAuthIdentifier()));
        return $result;
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \App\Http\Requests\StoreImageManipulationRequest  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(StoreImageManipulationRequest $request)
    // {
    //     //
    // }

    /**
     * 
     */
    protected function saveFormImage($image) {
        $absoluteImagePath = public_path("images");
        $imageName = $image->getClientOriginalName();
        $imageContainingDirName = explode(".", $imageName)[0];
        $imageContainingDir = $absoluteImagePath . "/" .$imageContainingDirName . "/";
        $originalFullPath = $imageContainingDir . $imageName;
        $image->move($imageContainingDir, $imageName);
        return [$imageName, $imageContainingDirName, $originalFullPath];
    }

    protected function resolveEdittedImagePath($originalPath) {
        $outputName = pathinfo($originalPath, PATHINFO_FILENAME) . "-[m]." . pathinfo($originalPath, PATHINFO_EXTENSION);
        $outputPath = pathinfo($originalPath, PATHINFO_DIRNAME) . "/" . $outputName;
        return [$outputName, $outputPath];
    }

    protected function populateDataObject($type, $imageContainingDirName, $originalName, $newName) {
        return [
            "type" => $type,
            "original_name" => $originalName,
            "original_path" => "images/" . $imageContainingDirName . "/" . $originalName,
            "output_name" => $newName,
            "output_path" => "images/" . $imageContainingDirName . "/" . $newName,
            "user_id" => auth()->user()->getAuthIdentifier()
        ];
    }

    /**
     * 
     */
    public function resize(ResizeImageRequest $request) {
        $newWidth = $request["w"];
        $newHeight = $request["h"];
        $originalImage = $request["image"];
        list($originalName, $imageContainingDirName, $originalFullPath) = $this->saveFormImage($originalImage);

        $resizedImage = $this->resizeImage($newWidth, $newHeight, $originalFullPath);
        $newName = pathinfo($originalFullPath, PATHINFO_FILENAME) . "-[m]." . pathinfo($originalFullPath, PATHINFO_EXTENSION);
        $outputPath = pathinfo($originalFullPath, PATHINFO_DIRNAME) . "/" . $newName;
        $resizedImage->save($outputPath);

        $rowData = $this->populateDataObject(ImageManipulationController::TYPE_RESIZE, $imageContainingDirName, $originalName, $newName);

        return new ImageManipulationResource(ImageManipulation::create($rowData));
    }

    public function pixelate(PixelateImageRequest $request) {
        $size = $request["size"];
        $originalImage = $request["image"];
        list($originalName, $imageContainingDirName, $originalFullPath) = $this->saveFormImage($originalImage);
        $newImage = $this->pixelateImage($size, $originalFullPath);
        list($newName, $newPath) = $this->resolveEdittedImagePath($originalFullPath);
        $newImage->save($newPath);
        $rowData = $this->populateDataObject(ImageManipulationController::TYPE_PIXELATE, $imageContainingDirName, $originalName, $newName);
        return new ImageManipulationResource(ImageManipulation::create($rowData));
    }

    /**
     * 
     */
    public function greyscale(GreyscaleImageRequest $request) {
        $originalImage = $request["image"];
        list($originalName, $imageContainingDirName, $originalFullPath) = $this->saveFormImage($originalImage);
        list($newName, $newPath) = $this->resolveEdittedImagePath($originalFullPath);
        $newImage = $this->makeGreyscale($originalFullPath);
        $newImage->save($newPath);

        $rowData = $this->populateDataObject(ImageManipulationController::TYPE_GREYSCALE, $imageContainingDirName, $originalName, $newName);

        return new ImageManipulationResource(ImageManipulation::create($rowData));
    }

    public function compress(CompressImageRequest $request) {
        $originalImage = $request["image"];
        $ratio = $request["ratio"];
        list($originalName, $imageContainingDirName, $originalFullPath) = $this->saveFormImage($originalImage);
        list($newName, $newPath) = $this->resolveEdittedImagePath($originalFullPath);
        $ratio = $this->resolveCompressionRation($ratio);
        Image::make($originalFullPath)->save($newPath, $ratio, "jpg");

        $rowData = $this->populateDataObject(ImageManipulationController::TYPE_GREYSCALE, $imageContainingDirName, $originalName, $newName);

        return new ImageManipulationResource(ImageManipulation::create($rowData));
    }

    /**
     * @param string $ratio
     */
    protected function resolveCompressionRation($ratio) {
        return str_ends_with($ratio, "%") ? (double) substr_replace($ratio, "", -1) : $ratio;
    }

    /**
     * 
     */
    public function makeGreyscale($originalPath) {
        $image = Image::make($originalPath);
        return $image->greyscale();
    }

    /**
     * @param int $size
     */
    protected function pixelateImage($size, $originalPath) {
        $image = Image::make($originalPath);
        return $image->pixelate($size);
    }

    /**
     * 
     */
    protected function resizeImage($width, $height, $originalPath) {
        $image = Image::make($originalPath);
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        list($width, $height) = $this->resolveResizedImageDimensions($originalWidth, $originalHeight, $width, $height);

        return $image->resize($width,$height);
    }

    /**
     * @param int $originalWidth
     * @param int $originalHeight
     * @param string $width
     * @param string $height
     */
    protected function resolveResizedImageDimensions($originalWidth, $originalHeight, $width, $height) {
        $width = str_ends_with($width, "%") ? $originalWidth * ((double) substr_replace($width, "", -1)) / 100: $width;
        $height = $height ? (str_ends_with($height, "%") ? $originalHeight * ((double) substr_replace($height, "", -1)) / 100 : $height) :
        $originalHeight * $width / $originalWidth;
        return [$width, $height];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    public function show(ImageManipulation $image)
    {
        if(!Gate::allows("view", $image)) {
            return response("You don't own this image", 401);
        }
        return new ImageManipulationResource($image);
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \App\Http\Requests\UpdateImageManipulationRequest  $request
    //  * @param  \App\Models\ImageManipulation  $imageManipulation
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(UpdateImageManipulationRequest $request, ImageManipulation $imageManipulation)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageManipulation $image)
    {
        if(!Gate::allows("delete", $image)) {
            return response("You are not the owner of this image", 401);
        }
        $image->delete();
        return response("", 204);
    }
}
