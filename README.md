<p align="center"><img src="./images/logo.png" width="200" alt="Laravel Logo"></p>

<p align="center" style="font-weight: bold; font-size: 1.5em">Image Manipulation API</p>

## Description
This REST API provides end points to process images and apply certain available services on them (filters and compression), to edit an image user needs to be authenticated, also to view or delete an image he needs to be the owner. <br>
> **_Note:_** Admin users can view and delete any image for content maintainance purposes.
## **Instructions**
- Clone project:
```
git clone https://github.com/Ammarr5/Image-Manip-REST-API.git
```
- Install packages:
```
cd Image-Manip-REST-API
composer install
```
- Rename `.env.example` file to `.env` and configure database options.
- Start Server:
```
php artisan serve
```

## **Example**
<p align="center"><img src="./images/michael.png" width="200"/>
<br>
<p align="center" style="font-weight: bold; font-size: 1.2em">Original (649 KB)</p>
</p>
<br><br>

<div style="display: flex; justify-content: center;">
<div>
	<div style="width: 200px; display: inline-block">
		<img src="./images/michael-compressed[50].png" style="width: inherit; object-fit: contain;"><br>
		<p align="center" style="font-weight: bold; font-size: 1em;">compress ratio: 50 (35.5 KB)</p>
	</div>
	<div style="width: 200px; display: inline-block; margin-left: 1em;">
		<img src="./images/michael-pixelate[10].png" style="width: inherit; object-fit: contain;"><br>
		<p align="center" style="font-weight: bold; font-size: 1.2em;">pixelate size: 10</p>
	</div>
	<br>
	<div style="width: 200px; display: inline-block; margin-right: 1em;">
		<img src="./images/michael-resize[500].png" style="width: inherit; object-fit: contain;"><br>
		<p align="center" style="font-weight: bold; font-size: 1.2em;">resize width: 500</p>
	</div>
	<div style="width: 200px; display: inline-block">
		<img src="./images/michael-greyscale.png" style="width: inherit; object-fit: contain;"><br>
		<p align="center" style="font-weight: bold; font-size: 1.2em;">greyscale</p>
	</div>
</div>
<div>