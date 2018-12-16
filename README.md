# FattyChappy-s-repository
Just for testing purpose

This is source codes of my image managing applications.

-- Image Uploader --
https://ns-1441.meowwow.name/imgupload_guest/img_upload.html

-- Image List Viewer --
https://ns-1441.meowwow.name/imgupload_guest/imgmanager.php

Notice: These URLs are not available since I have not finished my contents yet...

addDB.php         -> add datas submitted by img_upload.html to the images list database

DBinfo.php        -> its name tells (database connection infomation not included)

img_resize.php    -> resize and optimize an image uploaded from img_upload.html (called by upload_process.php as function)

imgdel.php        -> delete the database fields and the image by clicking Delete button in imgmanager.php

imginfo.php       -> show information of image database field by clicking Info button in imgmanager.php

imgmanager.php    -> Show images list and manage images and data

info_process.php  -> modifying an image file and database process for imginfo.php

modifyDB.php      -> modify database fields called by info_process.php as function

upload_process.php -> uploading process - verify and upload an image submitted by img_upload.html then call functions in addDB.php and img_resize.php

