<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form">
            <?= $this->Form->create($user) ?>
                <fieldset>
                    <legend><?= __('Add User') ?></legend>
                    <?= $this->Form->control('email') ?>
                    <?= $this->Form->control('password') ?>
                    <?= $this->Form->control('role', [
                        'options' => ['admin' => 'Admin', 'author' => 'Author']
                    ]) ?>
                </fieldset>
            <?= $this->Form->button(__('Submit')); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
