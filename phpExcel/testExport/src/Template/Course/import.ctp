<?= $this->Form->create(null, [
    'url' => [
        'controller' => 'Course',
        'action' => 'import'
    ],
    'enctype' => 'multipart/form-data',
]) ?>
<input type="file" id="data_import" name="file_import">
<button>sub</button>
<?= $this->Form->end() ?>


<script>
    $(document).ready(function () {
        $('#data_import').change(function () {
            let formData = new FormData();
            console.log($(this)[0].files[0]);
            formData.append('file_import', $(this)[0].files[0]);
            $.ajax({
                url:'<?= $this->Url->build(['controller' => 'Course', 'action' => 'import']) ?>',
                method: 'Post',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-Token': <?= json_encode($this->request->getParam('_csrfToken')); ?>
                },
                enctype: 'multipart/form-data',
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    console.log(res.data);
                }
            })
        });
    })
</script>
