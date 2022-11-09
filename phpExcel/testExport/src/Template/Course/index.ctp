<a href="Course/create" class="btn btn-success m-2">Add Course</a>
<a href="Course/export" class="btn btn-success m-2">Export</a>
<table class="table table-striped">
    <tr>
        <th>ID</th>
        <th>Name Course</th>
        <th>Created At</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php if (!empty($data_course)) {
        foreach ($data_course as $v_course):?>
            <tr>
                <td><?= !empty($v_course->id) ? $v_course->id : '' ?></td>
                <td><?= !empty($v_course->name_course) ? $v_course->name_course : '' ?></td>
                <td><?= !empty($v_course->created_at) ? $v_course->created_at->format('d/m/Y') : '' ?></td>
                <td><a class="btn btn-success"
                       href="Course/edit/<?= !empty($v_course->id) ? $v_course->id : '' ?>">Edit</a></td>
                <td>
                    <?= $this->Form->create(null, [
                        'url' => [
                            'controller' => 'course',
                            'action' => 'delete',
                            !empty($v_course->id) ? $v_course->id : ''
                        ]
                    ]) ?>
                    <button
                        class="btn btn-danger"
                        data-name="<?= !empty($v_course->name_course) ? $v_course->name_course : '' ?>">Delete
                    </button>
                    <?= $this->Form->end() ?>

                </td>
            </tr>
        <?php endforeach;
    } ?>
</table>
