<?php
$this->extend('/Common/index_common');
$this->BreadCumbs->addCrumb('Alunos', '/alunos');
$this->BreadCumbs->addCrumb('Lista de Alunos', '/alunos/index');
?>

<?php $this->start('top-actions') ?>
<?php $this->end() ?>
<?php $this->assign('table-title', __('Avaliacoes deste Semestre')) ?>
<?php $this->start('filter-form') ?>
<?php echo $this->Form->create('Avaliacao', [
    'role'          => 'form',
    'enctype'       => 'multipart/form-data',
    'class'         => 'form-horizontal',
    'inputDefaults' => ['before' => '', 'after' => ''],
]); ?>
<div class="row">
    <div class="form-group">
        <div class="col-md-3">
            <?php echo $this->Form->input('curso_id', [
                'label'       => false,
                'div'         => false,
                'required'    => false,
                'class'       => 'form-control',
                'placeholder' => 'Curso',

            ]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->input('turma_id', [
                'label'       => false,
                'div'         => false,
                'required'    => false,
                'class'       => 'form-control',
                'placeholder' => 'Disciplina',
            ]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->input('Curso.unidade_organica_id', [
                'label'       => false,
                'div'         => false,
                'required'    => false,
                'class'       => 'form-control',
                'placeholder' => 'Ou Unidade Organica',
                'empty'       => '',
            ]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->end([
                'label' => __('Pesquisar', true),
                'class' => 'btn btn-blue next-step btn-block',
            ]); ?>
        </div>
    </div>
</div>


<?php $this->end() ?>
<?php $this->start('table-header') ?>

<tr>
    <th><?php echo __('Turma') ?></th>
    <th><?php echo __('Curso') ?></th>
    <th><?php echo __('Faculdade') ?></th>
    <th><?php echo __('Data de Realizacao') ?></th>
    <th><?php echo __('Tipo de Avaliacao') ?></th>
    <th><?php echo __('Peso da Avaliacao') ?></th>
    <th><?php echo __('Accoes') ?></th>
</tr>
<?php $this->end() ?>
<?php $this->start('table-body') ?>
<?php foreach ($avaliacaos as $avaliacao):?>
    <tr>
        <td><?php echo $curso['Curso']['codigo']; ?>&nbsp;</td>
        <td><?php echo $curso['Curso']['name']; ?>&nbsp;</td>
        <td><?php echo $curso['UnidadeOrganica']['name'] ?>&nbsp;</td>


    </tr>
<?php endforeach; ?>
<?php $this->end() ?>





<div class="projectos index" id="center-column">
    <div class="top-bar">
        <?php if ($grupo == 1) {
            echo $this->Html->link(sprintf(__('Novo Registo de Notas', true)), ['action' => 'registo_de_notas'],
                    ['class' => 'button']);
        } ?>
        <h1><?php echo __('Avaliacoes'); ?></h1>
        <div class="breadcrumbs"><?php ?></div>
    </div>

    <div class="table">
        <table cellpadding="0" cellspacing="0" class="listing">
            <tr>

                <th><?php echo $this->Paginator->sort('Codigo', 'codigo'); ?></th>
                <th><?php echo $this->Paginator->sort('Nome do Aluno', 'name'); ?></th>
                <th><?php echo $this->Paginator->sort('Tipo Avalicao', 't0015tipoavaliacao_id'); ?></th>
                <th><?php echo $this->Paginator->sort('Nota', 'nota'); ?></th>
                <th><?php echo $this->Paginator->sort('Data', 'data'); ?></th>
                <th class="actions"><?php echo __('Accao'); ?></th>
            </tr>
            <?php
                $i = 0;

                foreach ($avaliacaos as $avaliacao):
                    $class = null;
                    if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                    }

                    if (($grupo != 3) || ($grupo == 3 && $username == $codigo)) {
                        ;
                        ?>
                        <tr<?php echo $class; ?>>
                            <td><?php echo $codigo; ?>&nbsp;</td>
                            <td><?php echo $name; ?>&nbsp;</td>
                            <td>
                                <?php echo $this->Html->link($avaliacao['TipoAvaliacao']['name'], [
                                        'controller' => 'tipoavaliacaos',
                                        'action'     => 'view',
                                        $avaliacao['TipoAvaliacao']['id'],
                                ]); ?>
                            </td>

                            <td><?php echo $avaliacao['Avaliacao']['nota']; ?>&nbsp;</td>
                            <td><?php echo $avaliacao['Avaliacao']['data']; ?>&nbsp;</td>
                            <td class="accoes">
                                <?php //echo $this->Html->image("/img/login-icon.gif", array("alt" => "Brownies",'url' => array('action' => 'view', $avaliacao['Avaliacao']['id'])));
                                ?>
                                <?php //echo $this->Html->image("/img/edit-icon.gif", array("alt" => "Brownies",'url' => array('action' => 'edit', $avaliacao['Avaliacao']['id'])));
                                ?>
                                <?php if ($grupo == 1) {
                                    echo $this->Html->image("/img/hr.gif", [
                                            "alt" => "Brownies",
                                            'url' => ['action' => 'delete', $avaliacao['Avaliacao']['id']],
                                            null,
                                            sprintf(__('Tem a certeza que deseja eliminar # %s?', true),
                                                    $avaliacao['Avaliacao']['id']),
                                    ]);
                                } ?>
                            </td>

                        </tr>
                        <?php
                    }
                endforeach; ?>
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