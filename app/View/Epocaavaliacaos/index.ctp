<div class="epocaAvaliacaos index">
    <h2><?php echo __('Epoca Avaliacaos'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('codigo'); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($epocaAvaliacaos as $epocaAvaliacao): ?>
            <tr>
                <td><?php echo h($epocaAvaliacao['EpocaAvaliacao']['id']); ?>&nbsp;</td>
                <td><?php echo h($epocaAvaliacao['EpocaAvaliacao']['codigo']); ?>&nbsp;</td>
                <td><?php echo h($epocaAvaliacao['EpocaAvaliacao']['name']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'),
                            ['action' => 'view', $epocaAvaliacao['EpocaAvaliacao']['id']]); ?>
                    <?php echo $this->Html->link(__('Edit'),
                            ['action' => 'edit', $epocaAvaliacao['EpocaAvaliacao']['id']]); ?>
                    <?php echo $this->Form->postLink(__('Delete'),
                            ['action' => 'delete', $epocaAvaliacao['EpocaAvaliacao']['id']], [],
                            __('Are you sure you want to delete # %s?', $epocaAvaliacao['EpocaAvaliacao']['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p>
        <?php
            echo $this->Paginator->counter([
                    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'),
            ]);
        ?>    </p>
    <div class="paging">
        <?php
            echo $this->Paginator->prev('< ' . __('previous'), [], null, ['class' => 'prev disabled']);
            echo $this->Paginator->numbers(['separator' => '']);
            echo $this->Paginator->next(__('next') . ' >', [], null, ['class' => 'next disabled']);
        ?>
    </div>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Epoca Avaliacao'), ['action' => 'add']); ?></li>
    </ul>
</div>
