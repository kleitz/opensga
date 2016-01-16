<?php
$this->BreadCumbs->addCrumb('Turmas', '/turmas');
$this->BreadCumbs->addCrumb($turma['Turma']['name'], '/turmas/ver_turma/' . $turma['Turma']['id']);
$this->BreadCumbs->addCrumb(__('Adicionar Docente'), '#');


?>

<?php echo $this->Form->create('DocenteTurma', array('role' => "form", 'novalidade' => 'novalidate', 'enctype' => 'multipart/form-data', 'class' => 'smart-wizard form-horizontal', 'id' => 'form', 'inputDefaults' => array('empty' => 'Seleccione', 'data-placeholder' => 'Seleccione'))); ?>
<div class="row">
	<div class="col-sm-6">
		<!-- start: TEXT AREA PANEL -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i>
				Informacoes da Turma
				<div class="panel-tools">
					<a href="#" class="btn btn-xs btn-link panel-collapse collapses">
					</a>
					<a data-toggle="modal" href="#panel-config" class="btn btn-xs btn-link panel-config">
						<i class="fa fa-wrench"></i>
					</a>
					<a href="#" class="btn btn-xs btn-link panel-refresh">
						<i class="fa fa-refresh"></i>
					</a>
					<a href="#" class="btn btn-xs btn-link panel-expand">
						<i class="fa fa-resize-full"></i>
					</a>
					<a href="#" class="btn btn-xs btn-link panel-close">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
            <div class="panel-body">
                <table class="table table-condensed table-hover">
                    <tbody>
                    <tr>
                        <td><?php echo __('Nome do Curso') ?></td>
                        <td><?php echo h($turma['Curso']['name']) ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Nome da Disciplina') ?></td>
                        <td><?php echo h($turma['Disciplina']['name']) ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Ano Curricular') ?></td>
                        <td><?php echo h($turma['Turma']['ano_curricular']) ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Semestre Curricular') ?></td>
                        <td><?php echo h($turma['Turma']['semestre_curricular']) ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Ano Lectivo') ?></td>
                        <td><?php echo h($turma['AnoLectivo']['ano']) ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Semestre Lectivo') ?></td>
                        <td><?php echo h($turma['SemestreLectivo']['semestre']) ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Total de Alunos') ?></td>
                        <td><?php echo h($turma['Turma']['total_alunos']) ?></td>
                    </tr>
                    </tbody>
                </table>
                <p>
                    <?php echo $this->Html->link('<i class="fa fa-plus"></i>Ver Turma', array('controller'=>'turmas','action' => 'ver_turma', $turma['Turma']['id']), array('class' => 'btn btn-green', 'escape' => false)) ?>
                </p>

            </div>
        </div>
		<!-- end: TEXT AREA PANEL -->
	</div>

    <div class="col-sm-6">
        <!-- start: TEXT AREA PANEL -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-external-link-square"></i>
                Informacoes do Docente
                <div class="panel-tools">
                    <a href="#" class="btn btn-xs btn-link panel-collapse collapses">
                    </a>
                    <a data-toggle="modal" href="#panel-config" class="btn btn-xs btn-link panel-config">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a href="#" class="btn btn-xs btn-link panel-refresh">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <a href="#" class="btn btn-xs btn-link panel-expand">
                        <i class="fa fa-resize-full"></i>
                    </a>
                    <a href="#" class="btn btn-xs btn-link panel-close">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $this->Form->label('docente_id', 'Nome do Docente'); ?>
                        <?php echo $this->Form->input('docente_id', array('label' => false, 'div' => false, 'class' => 'form-control search-select')); ?>
                    </div>
                    <div class="col-sm-12">
                        <?php echo $this->Form->label('tipo_docente_turma_id', 'Tipo de Docente'); ?>
                        <?php echo $this->Form->input('tipo_docente_turma_id', array('label' => false, 'div' => false, 'class' => 'form-control', 'id' => 'codigo')); ?>
                    </div>

                </div>
                <?php echo $this->Form->input('estado_docente_turma_id', array('type' => 'hidden', 'value' => 1, 'label' => false, 'div' => '_30', 'readonly' => true)); ?>
                <?php echo $this->Form->input('turma_id', array('type' => 'hidden', 'value' => $turmaId, 'label' => false, 'div' => '_30', 'readonly' => true)); ?>
                <div class="form-group">

                    <?php echo $this->Form->end(array('label' => __('GRAVAR', true), 'class' => 'btn btn-success finish-step btn-block', 'div' => 'col-sm-2 col-sm-offset-8')); ?>

                </div>

            </div>
        </div>
        <!-- end: TEXT AREA PANEL -->
    </div>

</div>
</form>