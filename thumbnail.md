# dùng để giảm dung lượng file hình ảnh upload lên

# Code ví dụ

```php
    public function _getThumbnailServices()
    {
        if (!empty($this->avatar)){
            $name_img = $this->avatar;
            $file = file_get_contents($name_img);
            $dir_thumbnail = 'upload/articles/thumbnail';
            $name_file = $dir_thumbnail . '/'. basename($name_img);

            if (file_exists($name_file))
            {
                return $name_file;
            }

            if ( !is_dir( $dir_thumbnail ) ) {
                mkdir( $dir_thumbnail, 0777 );
            }
            $pic = imagecreatefromstring($file);

            $width = imagesx($pic);
            $height = imagesy($pic);

            $thumbWight = $width / 6;
            $thumbHeight = $height / 6;

            $thumbnail = imagecreatetruecolor($thumbWight, $thumbHeight);

            imagecopyresampled($thumbnail, $pic, 0, 0,0, 0, $thumbWight, $thumbHeight, $width, $height);
            // save
            imagejpeg($thumbnail, $name_file);

            imagedestroy($pic);
            imagedestroy($thumbnail);

           return $name_file;
        }
        return null;
    }
```
