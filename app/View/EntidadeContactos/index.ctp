<div class="entidadeContactos index">
    <h2><?php echo __('Entidade Contactos'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('entidade_id'); ?></th>
            <th><?php echo $this->Paginator->sort('tipo_contacto_id'); ?></th>
            <th><?php echo $this->Paginator->sort('valor'); ?></th>
            <th><?php echo $this->Paginator->sort('estado_objecto_id'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo $this->Paginator->sort('modified'); ?></th>
            <th><?php echo $this->Paginator->sort('created_by'); ?></th>
            <th><?php echo $this->Paginator->sort('modified_by'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php
            foreach ($entidadeContactos as $entidadeContacto): ?>
                <tr>
                    <td><?php echo h($entidadeContacto['EntidadeContacto']['id']); ?>&nbsp;</td>
                    <td>
                        <?php echo $this->Html->link($entidadeContacto['Entidade']['name'], [
                                'controller' => 'entidades',
                                'action'     => 'view',
                                $entidadeContacto['Entidade']['id'],
                        ]); ?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($entidadeContacto['TipoContacto']['name'], [
                                'controller' => 'tipo_contactos',
                                'action'     => 'view',
                                $entidadeContacto['TipoContacto']['id'],
                        ]); ?>
                    </td>
                    <td><?php echo h($entidadeContacto['EntidadeContacto']['valor']); ?>&nbsp;</td>
                    <td>
                        <?php echo $this->Html->link($entidadeContacto['EstadoObjecto']['name'], [
                                'controller' => 'estado_objectos',
                                'action'     => 'view',
                                $entidadeContacto['EstadoObjecto']['id'],
                        ]); ?>
                    </td>
                    <td><?php echo h($entidadeContacto['EntidadeContacto']['created']); ?>&nbsp;</td>
                    <td><?php echo h($entidadeContacto['EntidadeContacto']['modified']); ?>&nbsp;</td>
                    <td><?php echo h($entidadeContacto['EntidadeContacto']['created_by']); ?>&nbsp;</td>
                    <td><?php echo h($entidadeContacto['EntidadeContacto']['modified_by']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View'),
                                ['action' => 'view', $entidadeContacto['EntidadeContacto']['id']]); ?>
                        <?php echo $this->Html->link(__('Edit'),
                                ['action' => 'edit', $entidadeContacto['EntidadeContacto']['id']]); ?>
                        <?php echo $this->Form->postLink(__('Delete'),
                                ['action' => 'delete', $entidadeContacto['EntidadeContacto']['id']], null,
                                __('Are you sure you want to delete # %s?',
                                        $entidadeContacto['EntidadeContacto']['id'])); ?>
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
        <li><?php echo $this->Html->link(__('New Entidade Contacto'), ['action' => 'add']); ?></li>
        <li><?php echo $this->Html->link(__('List Entidades'),
                    ['controller' => 'entidades', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Entidade'), ['controller' => 'entidades', 'action' => 'add']); ?> </li>
        <li><?php echo $this->Html->link(__('List Tipo Contactos'),
                    ['controller' => 'tipo_contactos', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Tipo Contacto'),
                    ['controller' => 'tipo_contactos', 'action' => 'add']); ?> </li>
        <li><?php echo $this->Html->link(__('List Estado Objectos'),
                    ['controller' => 'estado_objectos', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Estado Objecto'),
                    ['controller' => 'estado_objectos', 'action' => 'add']); ?> </li>
    </ul>
</div>
