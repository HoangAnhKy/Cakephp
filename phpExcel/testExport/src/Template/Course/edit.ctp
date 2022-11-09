<?= $this->Form->create(null, [
    'url' => [
        'controller' => 'Course',
        'action' => 'edit',
        !empty($data_course->id) ? $data_course->id : ''
    ]
]) ?>
<input type="text" class="form-control m-2" placeholder="Name Course" name="name_course"
       value="<?= !empty($data_course->name_course) ? $data_course->name_course : '' ?>"/>
<div class="d-block">
    <?php
    if (!empty($error['name_course'])) {
        foreach ($error['name_course'] as $v_error):?>
            <?= '<p class="text-danger m-2">'. $v_error .'<p/>' ?>
        <?php endforeach;
    } ?>
</div>
<button class="form-control btn btn-success m-2 ">submit</button>
<?= $this->Form->end() ?>
