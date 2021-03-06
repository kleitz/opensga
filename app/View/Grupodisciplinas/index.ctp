<

<div class="actions" id="left-column">
    <h3><?php echo __('Detalhes Adicionais'); ?></h3>
    <br/>
    <?php echo $this->Html->link(sprintf(__('Novo Grupo Disciplinar', true)),
            ['controller' => 'Grupodisciplina', 'action' => 'add'], ['class' => 'link']); ?>
    <?php echo $this->Html->link(sprintf(__('Lista De Disciplinas', true)),
            ['controller' => 't0004disciplinas', 'action' => 'index'], ['class' => 'link']); ?>
    <?php echo $this->Html->link(sprintf(__('Nova Disciplina', true)),
            ['controller' => 't0004disciplinas', 'action' => 'add'], ['class' => 'link']); ?>

</div>
<div class="projectos index" id="center-column">
    <div class="top-bar">
        <?php echo $this->Html->link(sprintf(__('Novo Curso', true)), ['action' => 'add'], ['class' => 'button']); ?>
        <h1><?php echo __('Curso'); ?></h1>
        <div class="breadcrumbs"><?php ?></div>
    </div>

    <div class="table">
        <table cellpadding="0" cellspacing="0" class="listing">
            <tr>
                <th><?php echo $this->Paginator->sort('Codigo', 'id'); ?></th>
                <th><?php echo $this->Paginator->sort('Ordem', 'ordem'); ?></th>
                <th><?php echo $this->Paginator->sort('Disciplina', 't0004disciplina_id'); ?></th>
                <th><?php echo $this->Paginator->sort('Creditos', 'creditos'); ?></th>
                <th><?php echo $this->Paginator->sort('Grupo Disciplinar', 'grupodisciplinasprec'); ?></th>
                <th class="actions"><?php echo __('Accoes'); ?></th>
            </tr>
            <?php
                $i = 0;
                foreach ($grupodisciplinas as $grupodisciplina):
                    $class = null;
                    if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                    }
                    ?>
                    <tr<?php echo $class; ?>>
                        <td><?php echo $grupodisciplina['Grupodisciplina']['id']; ?>&nbsp;</td>
                        <td><?php echo $grupodisciplina['Grupodisciplina']['ordem']; ?>&nbsp;</td>
                        <td>
                            <?php echo $this->Html->link($grupodisciplina['Disciplina']['name'], [
                                    'controller' => 't0004disciplinas',
                                    'action'     => 'view',
                                    $grupodisciplina['Disciplina']['id'],
                            ]); ?>
                        </td>
                        <td><?php echo $grupodisciplina['Grupodisciplina']['creditos']; ?>&nbsp;</td>
                        <td><?php echo $grupodisciplina['Grupodisciplina']['grupodisciplinasprec']; ?>&nbsp;</td>
                        <td class="accoes">
                            <?php echo $this->Html->image("/img/login-icon.gif", [
                                    "alt" => "Brownies",
                                    'url' => ['action' => 'view', $grupodisciplina['Grupodisciplina']['id']],
                            ]); ?>
                            <?php echo $this->Html->image("/img/edit-icon.gif", [
                                    "alt" => "Brownies",
                                    'url' => ['action' => 'edit', $grupodisciplina['Grupodisciplina']['id']],
                            ]); ?>
                            <?php echo $this->Html->image("/img/hr.gif", [
                                    "alt" => "Brownies",
                                    'url' => ['action' => 'delete', $grupodisciplina['Grupodisciplina']['id']],
                                    null,
                                    sprintf(__('Tem a certeza que deseja eliminar # %s?', true),
                                            $grupodisciplina['Grupodisciplina']['id']),
                            ]); ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
        </table>

    </div>
    <p>
        <?php
            //echo $this->Paginator->counter(array(
            //'format' => __('Page %page% of %pages%, Mostrando %current% linhas. Total: %count% linhas retornadas, starting on record %start%, ending on %end%', true)
            //));
        ?></p>

    <div class="paging">
        <?php echo $this->Paginator->prev('<<', [], null, ['class' => 'disabled']); ?>
        | <?php echo $this->Paginator->numbers(); ?>
        <?php echo $this->Paginator->next('>>', [], null, ['class' => 'disabled']); ?>
    </div>

</div>
