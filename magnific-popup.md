# công dụng

-   dùng để hiện image lên

=> code, lưu ý nhớ thêm thẻ a trước image và thêm thẻ div.list-images chứa toàn bộ thẻ a đó.

```php
// script js
$(document).ready(function () {
        $('.list-images').magnificPopup({
            delegate: 'a',
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: false,
            mainClass: 'mfp-with-zoom mfp-img-mobile',
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true,
                duration: 300, // don't foget to change the duration also in CSS
                opener: function(element) {
                    return element.find('img');
                }
            }
        });
    })
```

```php
//view

if(!empty($request->image_request)) {
        echo '<div class="form-title mx-3">Image of Request for Quotation</div><div class="card m-3 rounded-0"><div>';
        echo '<div style="border: none;" class="list-images d-flex flex-row flex-wrap mt-2 mb-2" >';
        foreach ($request->image_request as $img) {
            echo '<div class="img-contain mx-2 mt-3"> <a id="item-image" href="' . $img->url . '"> <img class="image_request" src="' . $img->url . '"> </a> </div>';
        }
        echo '</div>';
        echo '</div></div>';
    }
```
