<div class="smsNotifications index">
    <h2><?php echo __('Sms Notifications'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('phone_number'); ?></th>
            <th><?php echo $this->Paginator->sort('message'); ?></th>
            <th><?php echo $this->Paginator->sort('status'); ?></th>
            <th><?php echo $this->Paginator->sort('sms_notification_type_id'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($smsNotifications as $smsNotification): ?>
            <tr>
                <td><?php echo h($smsNotification['SmsNotification']['id']); ?>&nbsp;</td>
                <td><?php echo h($smsNotification['SmsNotification']['phone_number']); ?>&nbsp;</td>
                <td><?php echo h($smsNotification['SmsNotification']['message']); ?>&nbsp;</td>
                <td><?php echo h($smsNotification['SmsNotification']['status']); ?>&nbsp;</td>
                <td>
                    <?php echo $this->Html->link($smsNotification['SmsNotificationType']['name'], [
                            'controller' => 'sms_notification_types',
                            'action'     => 'view',
                            $smsNotification['SmsNotificationType']['id'],
                    ]); ?>
                </td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'),
                            ['action' => 'view', $smsNotification['SmsNotification']['id']]); ?>
                    <?php echo $this->Html->link(__('Edit'),
                            ['action' => 'edit', $smsNotification['SmsNotification']['id']]); ?>
                    <?php echo $this->Form->postLink(__('Delete'),
                            ['action' => 'delete', $smsNotification['SmsNotification']['id']], null,
                            __('Are you sure you want to delete # %s?', $smsNotification['SmsNotification']['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
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
        <li><?php echo $this->Html->link(__('New Sms Notification'), ['action' => 'add']); ?></li>
        <li><?php echo $this->Html->link(__('List Sms Notification Types'),
                    ['controller' => 'sms_notification_types', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Sms Notification Type'),
                    ['controller' => 'sms_notification_types', 'action' => 'add']); ?> </li>
    </ul>
</div>
