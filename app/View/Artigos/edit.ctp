<div class="artigos form">
<?php echo $this->Form->create('Artigo'); ?>
	<fieldset>
		<legend><?php echo __('Edit Artigo'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('entidade_id');
		echo $this->Form->input('data_publicacao');
		echo $this->Form->input('conteudo');
		echo $this->Form->input('titulo');
		echo $this->Form->input('resumo');
		echo $this->Form->input('estado_objecto_id');
		echo $this->Form->input('artigo_estado_artigo_id');
		echo $this->Form->input('slug');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Artigo.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Artigo.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Artigos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Entidades'), array('controller' => 'entidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entidade'), array('controller' => 'entidades', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Estado Objectos'), array('controller' => 'estado_objectos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estado Objecto'), array('controller' => 'estado_objectos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Artigo Estado Artigos'), array('controller' => 'artigo_estado_artigos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Artigo Estado Artigo'), array('controller' => 'artigo_estado_artigos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Artigo Area Academicas'), array('controller' => 'artigo_area_academicas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Artigo Area Academica'), array('controller' => 'artigo_area_academicas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Artigo Area Pesquisas'), array('controller' => 'artigo_area_pesquisas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Artigo Area Pesquisa'), array('controller' => 'artigo_area_pesquisas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Artigo Metas'), array('controller' => 'artigo_metas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Artigo Meta'), array('controller' => 'artigo_metas', 'action' => 'add')); ?> </li>
	</ul>
</div>