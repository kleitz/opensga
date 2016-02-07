<div class="cursoResponsavels index">
    <h2><?php echo __('Curso Responsavels'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('curso_id'); ?></th>
            <th><?php echo $this->Paginator->sort('funcionario_id'); ?></th>
            <th><?php echo $this->Paginator->sort('estado_objecto_id'); ?></th>
            <th><?php echo $this->Paginator->sort('data_inicio'); ?></th>
            <th><?php echo $this->Paginator->sort('data_fim'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cursoResponsavels as $cursoResponsavel): ?>
            <tr>
                <td><?php echo h($cursoResponsavel['CursoResponsavel']['id']); ?>&nbsp;</td>
                <td>
                    <?php echo $this->Html->link($cursoResponsavel['Curso']['name'],
                            ['controller' => 'cursos', 'action' => 'view', $cursoResponsavel['Curso']['id']]); ?>
                </td>
                <td>
                    <?php echo $this->Html->link($cursoResponsavel['Funcionario']['id'], [
                            'controller' => 'funcionarios',
                            'action'     => 'view',
                            $cursoResponsavel['Funcionario']['id'],
                    ]); ?>
                </td>
                <td>
                    <?php echo $this->Html->link($cursoResponsavel['EstadoObjecto']['name'], [
                            'controller' => 'estado_objectos',
                            'action'     => 'view',
                            $cursoResponsavel['EstadoObjecto']['id'],
                    ]); ?>
                </td>
                <td><?php echo h($cursoResponsavel['CursoResponsavel']['data_inicio']); ?>&nbsp;</td>
                <td><?php echo h($cursoResponsavel['CursoResponsavel']['data_fim']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'),
                            ['action' => 'view', $cursoResponsavel['CursoResponsavel']['id']]); ?>
                    <?php echo $this->Html->link(__('Edit'),
                            ['action' => 'edit', $cursoResponsavel['CursoResponsavel']['id']]); ?>
                    <?php echo $this->Form->postLink(__('Delete'),
                            ['action' => 'delete', $cursoResponsavel['CursoResponsavel']['id']], [],
                            __('Are you sure you want to delete # %s?',
                                    $cursoResponsavel['CursoResponsavel']['id'])); ?>
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
        <li><?php echo $this->Html->link(__('New Curso Responsavel'), ['action' => 'add']); ?></li>
        <li><?php echo $this->Html->link(__('List Cursos'), ['controller' => 'cursos', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Curso'), ['controller' => 'cursos', 'action' => 'add']); ?> </li>
        <li><?php echo $this->Html->link(__('List Funcionarios'),
                    ['controller' => 'funcionarios', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Funcionario'),
                    ['controller' => 'funcionarios', 'action' => 'add']); ?> </li>
        <li><?php echo $this->Html->link(__('List Estado Objectos'),
                    ['controller' => 'estado_objectos', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Estado Objecto'),
                    ['controller' => 'estado_objectos', 'action' => 'add']); ?> </li>
    </ul>
</div>
