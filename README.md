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

<p align="center">
	<img src="./images/michael-compressed[50].png" width="200"><br>
	<p align="center" style="font-weight: bold; font-size: 1.2em;">compress ratio: 50 (35.5 KB)</p>
</p><br>
<p align="center">
	<img src="./images/michael-pixelate[10].png" width="200"><br>
	<p align="center" style="font-weight: bold; font-size: 1.2em;">pixelate size: 10</p>
</p>
<br>
<p align="center">
	<img src="./images/michael-resize[500].png" width="200"><br>
	<p align="center" style="font-weight: bold; font-size: 1.2em;">resize width: 500</p>
</p>
<br>
<p align="center">
	<img src="./images/michael-greyscale.png" width="200"><br>
	<p align="center" style="font-weight: bold; font-size: 1.2em;">greyscale</p>
</p>