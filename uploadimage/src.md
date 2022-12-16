# table

```php
     /*save img*/
    public function saveImgInWebRood($image, $path = 'upload/tmp/')
    {
        $fileName = $image->getClientFileName();
        $extention = explode('.', $fileName);
        $ext = $extention[count($extention) - 1];
        $size = $image->getSize();
        $uploadPath = $path;
        $uploadFile = $uploadPath . 'material' . '-' . $fileName;

        if (!file_exists($path)) {
            mkdir($path);
        }
        $image->moveTo($uploadFile);
        return array($uploadFile, $size, $ext, $fileName);
    }

    /*add image*/
    public function addImage($data = array(), $materialID = null, $created_by = null)
    {
        try {
//            dd($data);
            $Listimage = $data ?? [];
            $path = 'upload/material/';
            $path_old = '';
            if (!file_exists($path)) {
                mkdir($path);
            }
            foreach ((array)$Listimage as $image) {
                $image = (object)$image;

                $array_url_temp = explode('/', $image->src);
                $url = $path  . $array_url_temp[count($array_url_temp) - 1];
                unset($array_url_temp[count($array_url_temp) - 1]);
                $path_old = implode('/', $array_url_temp );
                    copy($image->src, $url);
                $s_image = array();
                $s_image['material_id'] = $materialID;
                $s_image['url'] = $url;
                $s_image['size'] = $image->size;
                $s_image['extension'] = $image->extension;
                $s_image['created_by'] = $created_by;
                $saveData = $this->newEntity($s_image);
                if (!$this->save($saveData)) {
                    return false;
                }
            }

            $this->rrmdir($path_old);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    /*delete directory */
    function rrmdir($src = 'upload/tmp/') {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
    /*edit image*/
    public function editImage($id = null, $update_by = null)
    {
        try {
            $dataImage = $this->find()->select()->where(['id' => $id])->first();
            $dataImage->del_flag = DEL_FLAG;
            $dataImage->updated_by = $update_by;
            return $this->save($dataImage);
        } catch (\Exception $e) {
            return false;
        }
    }
```

# controller

```php
   public function addMaterial($data_request = array())
    {
        $validation = new ValidationCreateMaterial();
        $errors = $validation->validate($data_request);
        if (empty($errors)) {
            if ($this->m_material->saveMaterial($data_request)) {
                $dataID = $this->m_material->getDataMaterial()->first();
                $getRequest = $this->reloadImgCreate($data_request);
                return $this->m_image_material->addImage($getRequest, $dataID->id, $data_request['created_by']);
            }
            return false;
        }
        $this->set('errors', $errors);
        $this->set('data_request', $data_request);
        return false;
    }


    public function reloadImgCreate($data_request)
    {
        $dataPreLoaded = array();
        $ListImgTmp = array();
        if (!empty($data_request['ListimgTmp'])) {
            $dataPreLoaded = json_decode($data_request['ListimgTmp']);
        }
        if (!empty($data_request['preloaded'])) {
            $value = json_decode($data_request['ListimgTmp']);
            $array_id_img = array();
            foreach ($value as $v_img) {
                array_push($array_id_img, array_values((array)$v_img)[0]);
            }
            $array_img_dif = array_diff($array_id_img, $data_request['preloaded']);
            if ($data_request['id'] === '') {
                foreach ($array_img_dif as $img) {
                    foreach ($dataPreLoaded as $key => $value) {
                        if ($value->id == $img) {
                            unset($dataPreLoaded[$key]);
                        }
                    }
                }
            }
        } else if (empty($data_request['preloaded'])) {
            foreach ($dataPreLoaded as $key => $value) {
                unset($dataPreLoaded[$key]);
            }
        }
        if (!empty($data_request['images']) && $data_request['images'][0]->getClientFileName()) {
            foreach ($data_request['images'] as $k_img => $v_img) {
                $dataImg = $this->m_image_material->saveImgInWebRood($v_img);
                array_push($ListImgTmp, ['id' => $k_img, 'src' => $dataImg[0], 'size' => $dataImg[1], 'extension' => $dataImg[2], 'nameFile' => $dataImg[3]]);
            }
        }
        $ListImgTmp = array_merge($dataPreLoaded, $ListImgTmp);
        return $ListImgTmp;
    }

    public function editDataMaterial($data_request = array())
    {
        $validation = new ValidationEditMaterial($data_request['id']);
        $errors = $validation->validate($data_request);
        if (empty($errors)) {
            if ($this->m_material->editMaterial($data_request)) {
                $listImgReload = $this->reloadImgEdit($data_request);

                $materialID = $data_request['id'];
                $data_old_image = json_decode($this->getListImage( $data_request['id'], ['id']), true);
                $array_id_img_old = $this->getIdinArray($data_old_image['listImg']);
                $filterId = array_filter($listImgReload, function ($value) {
                    return $value->material_id !== null;
                });
                $array_id_reload = $this->getIdinArray($filterId);
                $deleteList = array_diff( $array_id_img_old, $array_id_reload);

                foreach ($deleteList as $value){
                    $this->m_image_material->editImage($value, $this->user_login->id);
                }

                $imgSave = array_filter($listImgReload, function ($value) {
                    return $value->material_id === null;
                });
                return $this->m_image_material->addImage($imgSave, $materialID, $data_request['updated_by']);
            }
        }
        $this->set('errors', $errors);
        $this->set('data_request', $data_request);

        return false;
    }

    private function getIdinArray($data){
        $value = array_values($data);
        $array_id_img = array();
        foreach ($value as $v_img) {
            $v_img = (array) $v_img;
            $temp = array_merge($array_id_img, array($v_img['id']));
            $array_id_img = $temp;
        }
        return $array_id_img;
    }

    public function reloadImgEdit($data_request)
    {
        $dataPreLoaded = array();
        $ListImgTmp = array();
        if (!empty($data_request['ListimgTmp'])) {
            $dataPreLoaded = json_decode($data_request['ListimgTmp']);
        }

        if (!empty($data_request['preloaded'])) {
            $value = json_decode($data_request['ListimgTmp']);
            $array_id_img = array();
            foreach ($value as $v_img) {
                array_push($array_id_img, array_values((array)$v_img)[0]);
            }
            $array_img_dif = array_diff($array_id_img, $data_request['preloaded']);
            foreach ($array_img_dif as $img) {
                foreach ($dataPreLoaded as $key => $value) {
                    if ($value->id == $img) {
                        unset($dataPreLoaded[$key]);
                    }
                }
            }
        } else if (empty($data_request['preloaded'])) {
            foreach ($dataPreLoaded as $key => $value) {
                unset($dataPreLoaded[$key]);
            }
        }

        if (!empty($data_request['images']) && $data_request['images'][0]->getClientFileName()) {
            foreach ($data_request['images'] as $k_img => $v_img) {
                $dataImg = $this->m_image_material->saveImgInWebRood($v_img);
                array_push($ListImgTmp,(object) ['id' => $k_img, 'src' => $dataImg[0], 'material_id' => null, 'size' => $dataImg[1], 'extension' => $dataImg[2], 'nameFile' => $dataImg[3]]);
            }
        }
        $ListImgTmp = array_merge($dataPreLoaded, $ListImgTmp);
        return $ListImgTmp;
    }
```
