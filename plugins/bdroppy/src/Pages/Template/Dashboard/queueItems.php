<?php foreach ($queues as $queue): ?>
<div class="item">
    <?php if ($queue->type == "import") : ?>
        <div class="pd-image">
            <img src="<?= $queue->data->getImage() ?>" alt="">
        </div>
        <div class="pd-details">
            <div class="pd-title">
                <h4><?= $queue->data->name ?></h4>
                <span class="pd-type">in queue for import</span>
                <span class="pd-date"><?= bdroppy_ago_time($queue->create_at) ?></span>
            </div>
            <div class="models">
                <?php foreach ($queue->data->models as $model): ?>
                    <?php if($model->model != 'NOSIZE') : ?>
                        <span><?= $model->model ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php elseif ($queue->type == "update") : ?>
        <div class="pd-details">
            <div class="pd-title">
                <h4><?= $queue->id ?></h4>
                <span class="pd-type text-primary">in queue for update</span>
                <span class="pd-date"><?= bdroppy_ago_time($queue->create_at) ?></span>
            </div>
        </div>
    <?php elseif ($queue->type == "delete") : ?>

        <div class="pd-details">
            <div class="pd-title">
                <h4><?= $queue->id ?></h4>
                <span class="pd-type text-danger">in queue for delete</span>
                <span class="pd-date"><?= bdroppy_ago_time($queue->create_at) ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>