<div class="regimes index">
    <h2><?php echo __('Regimes'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($regimes as $regime): ?>
            <tr>
                <td><?php echo h($regime['Regime']['id']); ?>&nbsp;</td>
                <td><?php echo h($regime['Regime']['name']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), ['action' => 'view', $regime['Regime']['id']]); ?>
                    <?php echo $this->Html->link(__('Edit'), ['action' => 'edit', $regime['Regime']['id']]); ?>
                    <?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $regime['Regime']['id']],
                            ['confirm' => __('Are you sure you want to delete # %s?', $regime['Regime']['id'])]); ?>
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
        <li><?php echo $this->Html->link(__('New Regime'), ['action' => 'add']); ?></li>
    </ul>
</div>
