# FattyChappy-s-repository
For my portfolio

This is source codes of my image managing applications.

-- Files Description --

-- Image Uploader --
https://ns-1441.meowwow.name/en/imgupload_guest/img_upload.html

-- Image List Viewer --
https://ns-1441.meowwow.name/en/imgupload_guest/imgmanager.php

addDB.php         -> add datas submitted by img_upload.html to the images list database (called by upload_process.php as addImageDB function)

DBinfo.php        -> its name tells (database connection infomation not included)

img_resize.php    -> resize and optimize an image uploaded from img_upload.html (called by upload_process.php as ImageResize function)

imgdel.php        -> delete the database fields and the image by clicking Delete button in imgmanager.php

imginfo.php       -> show information of image database field by clicking Info button in imgmanager.php

imgmanager.php    -> show images list and manage images and data

info_process.php  -> modifying an image file and database process for imginfo.php

modifyDB.php      -> modify database fields called by info_process.php as modDB function

upload_process.php -> uploading process - verify and upload an image submitted by img_upload.html then call functions in addDB.php and img_resize.php

imgupload.js -> show confirm dialog or uploading image

imgmanager2.js -> lightbox function, delete from database and relevant image, control lazy-load

language.js -> rewrite the website data when language option drop down menu is changed

tooltip.js -> popping an image to show the purpose of the item (for thumbnails' object-position property) 

-- Contact form --
https://ns-1441.meowwow.name/en/contact.html

contact.html -> a mail form

contact.js -> show confirm dialog and verify submitting contents

form_process.php -> verify submitted contents further then send an email to website administrator

There are css files to set websites' visuals, too.
These file names tell all.
