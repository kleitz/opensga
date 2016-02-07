<div class="bolsaResultados view">
    <h2><?php echo __('Bolsa Resultado'); ?></h2>
    <dl>
        <dt><?php echo __('Id'); ?></dt>
        <dd>
            <?php echo h($bolsaResultado['BolsaResultado']['id']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Bolsa Candidatura'); ?></dt>
        <dd>
            <?php echo $this->Html->link($bolsaResultado['BolsaPedido']['id'], [
                    'controller' => 'bolsa_candidaturas',
                    'action'     => 'view',
                    $bolsaResultado['BolsaPedido']['id'],
            ]); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Bolsa Tipo Bolsa'); ?></dt>
        <dd>
            <?php echo $this->Html->link($bolsaResultado['BolsaTipoBolsa']['name'], [
                    'controller' => 'bolsa_tipo_bolsas',
                    'action'     => 'view',
                    $bolsaResultado['BolsaTipoBolsa']['id'],
            ]); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Bolsa Motivo Indeferimento'); ?></dt>
        <dd>
            <?php echo $this->Html->link($bolsaResultado['BolsaMotivoIndeferimento']['name'], [
                    'controller' => 'bolsa_motivo_indeferimentos',
                    'action'     => 'view',
                    $bolsaResultado['BolsaMotivoIndeferimento']['id'],
            ]); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Data Resultado'); ?></dt>
        <dd>
            <?php echo h($bolsaResultado['BolsaResultado']['data_resultado']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Created'); ?></dt>
        <dd>
            <?php echo h($bolsaResultado['BolsaResultado']['created']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Modified'); ?></dt>
        <dd>
            <?php echo h($bolsaResultado['BolsaResultado']['modified']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Created By'); ?></dt>
        <dd>
            <?php echo h($bolsaResultado['BolsaResultado']['created_by']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Modified By'); ?></dt>
        <dd>
            <?php echo h($bolsaResultado['BolsaResultado']['modified_by']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Observacoes'); ?></dt>
        <dd>
            <?php echo h($bolsaResultado['BolsaResultado']['observacoes']); ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Bolsa Resultado'),
                    ['action' => 'edit', $bolsaResultado['BolsaResultado']['id']]); ?> </li>
        <li><?php echo $this->Form->postLink(__('Delete Bolsa Resultado'),
                    ['action' => 'delete', $bolsaResultado['BolsaResultado']['id']], null,
                    __('Are you sure you want to delete # %s?', $bolsaResultado['BolsaResultado']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Bolsa Resultados'), ['action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Bolsa Resultado'), ['action' => 'add']); ?> </li>
        <li><?php echo $this->Html->link(__('List Bolsa Candidaturas'),
                    ['controller' => 'bolsa_candidaturas', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Bolsa Candidatura'),
                    ['controller' => 'bolsa_candidaturas', 'action' => 'add']); ?> </li>
        <li><?php echo $this->Html->link(__('List Bolsa Tipo Bolsas'),
                    ['controller' => 'bolsa_tipo_bolsas', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Bolsa Tipo Bolsa'),
                    ['controller' => 'bolsa_tipo_bolsas', 'action' => 'add']); ?> </li>
        <li><?php echo $this->Html->link(__('List Bolsa Motivo Indeferimentos'),
                    ['controller' => 'bolsa_motivo_indeferimentos', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Bolsa Motivo Indeferimento'),
                    ['controller' => 'bolsa_motivo_indeferimentos', 'action' => 'add']); ?> </li>
        <li><?php echo $this->Html->link(__('List Bolsa Bolsas'),
                    ['controller' => 'bolsa_bolsas', 'action' => 'index']); ?> </li>
        <li><?php echo $this->Html->link(__('New Bolsa Bolsa'),
                    ['controller' => 'bolsa_bolsas', 'action' => 'add']); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php echo __('Related Bolsa Bolsas'); ?></h3>
    <?php if (!empty($bolsaResultado['BolsaBolsa'])): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?php echo __('Id'); ?></th>
                <th><?php echo __('Aluno Id'); ?></th>
                <th><?php echo __('Bolsa Candidatura Id'); ?></th>
                <th><?php echo __('AnoLectivo Id'); ?></th>
                <th><?php echo __('Banco Id'); ?></th>
                <th><?php echo __('Nib'); ?></th>
                <th><?php echo __('Conta Bancaria'); ?></th>
                <th><?php echo __('Bolsa Fonte Bolsa Id'); ?></th>
                <th><?php echo __('Processo Bolsa'); ?></th>
                <th><?php echo __('Data Atribuicao'); ?></th>
                <th><?php echo __('Created'); ?></th>
                <th><?php echo __('Modified'); ?></th>
                <th><?php echo __('Bolsa Criador Conta Id'); ?></th>
                <th><?php echo __('Bolsa Estado Bolsa Id'); ?></th>
                <th><?php echo __('Created By'); ?></th>
                <th><?php echo __('Modified By'); ?></th>
                <th><?php echo __('Codigo'); ?></th>
                <th><?php echo __('Bolsa Resultado Id'); ?></th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
            <?php
                $i = 0;
                foreach ($bolsaResultado['BolsaBolsa'] as $bolsaBolsa): ?>
                    <tr>
                        <td><?php echo $bolsaBolsa['id']; ?></td>
                        <td><?php echo $bolsaBolsa['aluno_id']; ?></td>
                        <td><?php echo $bolsaBolsa['bolsa_candidatura_id']; ?></td>
                        <td><?php echo $bolsaBolsa['ano_lectivo_id']; ?></td>
                        <td><?php echo $bolsaBolsa['banco_id']; ?></td>
                        <td><?php echo $bolsaBolsa['nib']; ?></td>
                        <td><?php echo $bolsaBolsa['conta_bancaria']; ?></td>
                        <td><?php echo $bolsaBolsa['bolsa_fonte_bolsa_id']; ?></td>
                        <td><?php echo $bolsaBolsa['processo_bolsa']; ?></td>
                        <td><?php echo $bolsaBolsa['data_atribuicao']; ?></td>
                        <td><?php echo $bolsaBolsa['created']; ?></td>
                        <td><?php echo $bolsaBolsa['modified']; ?></td>
                        <td><?php echo $bolsaBolsa['bolsa_criador_conta_id']; ?></td>
                        <td><?php echo $bolsaBolsa['bolsa_estado_bolsa_id']; ?></td>
                        <td><?php echo $bolsaBolsa['created_by']; ?></td>
                        <td><?php echo $bolsaBolsa['modified_by']; ?></td>
                        <td><?php echo $bolsaBolsa['codigo']; ?></td>
                        <td><?php echo $bolsaBolsa['bolsa_resultado_id']; ?></td>
                        <td class="actions">
                            <?php echo $this->Html->link(__('View'),
                                    ['controller' => 'bolsa_bolsas', 'action' => 'view', $bolsaBolsa['id']]); ?>
                            <?php echo $this->Html->link(__('Edit'),
                                    ['controller' => 'bolsa_bolsas', 'action' => 'edit', $bolsaBolsa['id']]); ?>
                            <?php echo $this->Form->postLink(__('Delete'),
                                    ['controller' => 'bolsa_bolsas', 'action' => 'delete', $bolsaBolsa['id']], null,
                                    __('Are you sure you want to delete # %s?', $bolsaBolsa['id'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Bolsa Bolsa'),
                        ['controller' => 'bolsa_bolsas', 'action' => 'add']); ?> </li>
        </ul>
    </div>
</div>
