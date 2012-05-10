<?php
/**
 * OpenSGA - Sistema de Gestão Académica
 *   Copyright (C) 2010-2011  INFOmoz (Informática-Moçambique)
 * 
 * Este programa é um software livre: Você pode redistribuir e/ou modificar
 * todo ou parte deste programa, desde que siga os termos da licença por nele
 * estabelecidos. Grande parte do código deste programa está sob a licença 
 * GNU Affero General Public License publicada pela Free Software Foundation.
 * A versão original desta licença está disponível na pasta raiz deste software.
 * 
 * Este software é distribuido sob a perspectiva de que possa ser útil para 
 * satisfazer as necessidades dos seus utilizadores, mas SEM NENHUMA GARANTIA. Veja
 * os termos da licença GNU Affero General Public License para mais detalhes
 * 
 * As redistribuições deste software, mesmo quando o código-fonte for modificado significativamente,
 * devem manter está informação legal, assim como a licença original do software.
 * 
 * @copyright     Copyright 2010-2011, INFOmoz (Informática-Moçambique) (http://infomoz.net)
 * @link          http://infomoz.net/opensga CakePHP(tm) Project
 * @author		  Elisio Leonardo (http://infomoz.net/elisio-leonardo)
 * @package       opensga
 * @subpackage    opensga.core.controller
 * @since         OpenSGA v 0.10.0.0
 * @license       GNU Affero General Public License
 * 
 */
 App::uses('Sanitize', 'Utility');
 
class AlunosController extends AppController {

	var $name = 'Alunos';

	function index() {
		$this->Aluno->recursive = 0;
		 
            $this->set('alunos', $this->paginate(null, $this->_Filter));
		
		$escolas = $this->Aluno->Escola->find('list');
		$cursos = $this->Aluno->Curso->find('list');
		
		$this->set(compact('escolas','cursos'));
		$this->set('relatorio','aluno_relatorio');
		//$this->layout='3_colunas';
		
	}
        
            function index_ajax() {
		$this->Aluno->recursive = 0;
		
              
        
                $conditions = array();
                $conditions['limit']=Sanitize::escape( $_GET['iDisplayLength'] );
                $conditions['offset']=Sanitize::escape( $_GET['iDisplayStart'] );
                $alunos = $this->Aluno->find('all',$conditions);
                $alunos_count = $this->Aluno->find('count');
                $alunos_count_filter = $this->Aluno->find('count',$conditions);
                $iTotal=$alunos_count;
                $iFilteredTotal = $alunos_count_filter;
            $escolas = $this->Aluno->Escola->find('list');
            $cursos = $this->Aluno->Curso->find('list');
		
                
                $output = array(
                    "sEcho" => intval($_GET['sEcho']),
                    "iTotalRecords" => $iTotal,
                    "iTotalDisplayRecords" => $iFilteredTotal,
                    "aaData" => array()
                );
                
                
                $this->set('output',$output);
                $this->set('alunos',$alunos);
                
		
		
	}
        
        
	function view($id = null){
	        App::Import('Model','Matricula');
	        $Matricula = new Matricula;			
		if (!$id) {
			$this->Session->setFlash('ID Invalido', 'flasherror');
			$this->redirect(array('action' => 'index'));
		}

          $this->Aluno->contain(array(
              'Matricula'=>array(
                  'Planoestudo','Turno'
              ),
              'Curso','Provincia','Cidade','Paise','Genero','Documento'
          ));
          $aluno = $this->Aluno->find('first',array('conditions'=>array('Aluno.id'=>$id)));
          //debug($aluno);
         $this->Aluno->Inscricao->contain(array(
             'Turma'=>array(
                 'fields'=>array(
                     'id','disciplina_id','anocurricular','semestrecurricular'),
                     'Disciplina'=>array(
                         'fields'=>array('id','name')
                     )
                    ),
             'Matricula'=>array(
                 'fields'=>array('id','anolectivo_id'),
                 'Anolectivo'=>array(
                     'fields'=>array('id','ano')
                 )
                 
             )
                )
            ); 
        $inscricoes_activas = $this->Aluno->Inscricao->find('all',array('conditions'=>array('Inscricao.aluno_id'=>$id)));
        
         $this->Aluno->Inscricao->contain(array(
             'Turma'=>array(
                 'fields'=>array(
                     'id','disciplina_id','anocurricular','semestrecurricular'),
                     'Disciplina'=>array(
                         'fields'=>array('id','name')
                     )
                    ),
             'Matricula'=>array(
                 'fields'=>array('id','anolectivo_id'),
                 'Anolectivo'=>array(
                     'fields'=>array('id','ano')
                 )
                 
             )
                )
            );
        $todas_inscricoes = $this->Aluno->Inscricao->find('all',array('conditions'=>array('Inscricao.aluno_id'=>$id)));
         
         $this->Aluno->Inscricao->contain(array(
             'Turma'=>array(
                 'fields'=>array(
                     'id','disciplina_id','anocurricular','semestrecurricular'),
                     'Disciplina'=>array(
                         'fields'=>array('id','name')
                     )
                    ),
             'Matricula'=>array(
                 'fields'=>array('id','anolectivo_id'),
                 'Anolectivo'=>array(
                     'fields'=>array('id','ano')
                 )
                 
             )
                )
            );        
        $cadeiras_aprovadas = $this->Aluno->Inscricao->find('all',array('conditions'=>array('Inscricao.aluno_id'=>$id)));
        
        $this->Aluno->Pagamento->contain(array(
            'Tipopagamento'
        ));
        $pagamentos = $this->Aluno->Pagamento->find('all',array('conditions'=>array('Pagamento.aluno_id'=>$id)));
        //debug($pagamentos);
		$this->set('aluno',$aluno);
		$users = $this->Aluno->User->find('list');
		$paises = $this->Aluno->Paise->find('list');
		$cidades = $this->Aluno->Cidade->find('list');
		$provincias = $this->Aluno->Provincia->find('list');
        $provenienciacidades = $this->Aluno->Cidade->find('list');
		$proveniencianomes = $this->Aluno->Provincia->find('list');
		$documentos = $this->Aluno->Documento->find('list');
		$areatrabalhos = $this->Aluno->Areatrabalho->find('list');
		$generos = $this->Aluno->Genero->find('list');
        $cidadenascimentos = $this->Aluno->CidadeNascimento->find('list');
		$cursos = $this->Aluno->Curso->find('list');
		$planoestudos = $Matricula->Planoestudo->find('list');
		
		$this->set(compact('cursos','planoestudos','users', 'paises', 'cidades', 'provincias', 'documentos', 'areatrabalhos','generos','cidadenascimentos','proveniencianomes','provenienciacidades','inscricoes_activas','todas_inscricoes','cadeiras_aprovadas','pagamentos'));
	}


	function add() {
	        App::Import('Model','Matricula');
	        $Matricula = new Matricula;
			
			
		if (!empty($this->request->data)) {
				
			
			$data_matricula = array();
			$dados = $this->data;
			$this->Aluno->create();
                        $dados['Aluno']['codigo'] = $this->Aluno->geraCodigo();
                        $nome_foto = WWW_ROOT."\ffotos\\".$this->data['Aluno']['foto']['name'];
			$imagem=array($this->data['Aluno']['foto']);
			
			$fileOk = $this->uploadFiles('upload',$imagem);
			if(isset($fileOk['urls']) and $fileOk['urls']!=null){
			$dados['Aluno']['foto']=$fileOk['urls'][0];}
			else{
				$dados['Aluno']['foto']='';
			}
                        
                        $this->Aluno->User->create();
                        $dados['User']['username'] = $dados['Aluno']['codigo'];
                        $dados['User']['password'] = md5($dados['Aluno']['codigo']);
                        $dados['User']['codigocartao'] = $dados['Aluno']['codigo'];
						$dados['User']['name'] = $this->data['Aluno']['name'];
                        $dados['User']['group_id'] = 3;
                        $this->Aluno->User->save($dados);
                        $dados['Aluno']['user_id'] = $this->Aluno->User->getLastInsertID();
                        //$this->data['Aluno']['foto'] = $this->data['Aluno']['codigo'].".jpg";
                        $entidade_data = array('Entidade'=>$dados['Aluno']);
                        $this->Aluno->Entidade->create();
                        $this->Aluno->Entidade->save($entidade_data);
                        
                        $dados['Aluno']['entidade_id'] = $this->Aluno->Entidade->getLastInsertID();
                        /**
                         * Garantindo que escola numca seja null
                         * @todo Mais tarde remover isso
                         */
                        if(!isset($dados['Aluno']['escola_id'])){
                            $dados['Aluno']['escola_id']=1;
                        }
                        /**
                         *@todo Ajustar esses aros criados 
                         */
              
			if ($this->Aluno->save($dados)) {
				
            					/**
								 * Pega os dados da matricula e realiza a matricula
								 */                    
								 $data_matricula['aluno_id']= $this->Aluno->getInsertID();
								 $data_matricula['curso_id'] = $this->data['Aluno']['curso_id'];
								 $data_matricula['planoestudo_id'] = $this->data['Aluno']['planoestudo_id'];
								 $data_matricula['estadomatricula_id'] = 1;
								 $data_matricula['data'] = $this->data['Aluno']['dataingresso'];
								 $data_matricula['user_id'] = $this->Session->read('Auth.User.id');
								 $data_matricula['anolectivo_id'] = $this->Session->read('SGAConfig.anolectivo_id');
								 $data_matricula['turno_id'] = $this->data['Aluno']['turno_id'];
                                 $data_matricula['nivel'] = $this->data['Aluno']['nivel'];
                                 
								 $matricula_gravar=array('Matricula'=>$data_matricula);
								
								 if($Matricula->save($matricula_gravar)){
									  $this->loadModel('Pagamento');
									  
                                      $this->Pagamento->gerarPagamentos($this->Session->read('SGAConfig.anolectivo_id'),$this->Aluno->getLastInsertID(),$this->Session->read('SGAConfig.semestre'));
    								  $this->Session->setFlash('Aluno Registrado com sucesso</p><p>A matricula do aluno foi registrada com sucesso','flashok');
									  $this->redirect(array('controller'=>'inscricaos','action' => 'inscrever',  $this->Aluno->getInsertID(),$Matricula->getInsertID()));
									  
								} else {
									  $this->Session->setFlash('Erro ao registrar aluno. Por favor tente de novo.','flasherror');
								}
					}
		}
		$cursos = $this->Aluno->Curso->find('list');
		
		$planoestudos = $Matricula->Planoestudo->find('list');
		$users = $this->Aluno->User->find('list');
		$paises = $this->Aluno->Paise->find('list');
		$cidades = $this->Aluno->Cidade->find('list');
		$provincias = $this->Aluno->Provincia->find('list');
        $provenienciacidades = $this->Aluno->ProvenienciaCidade->find('list');
		$proveniencianomes = $this->Aluno->ProvenienciaProvincia->find('list');
		$documentos = $this->Aluno->Documento->find('list');
		$areatrabalhos = $this->Aluno->Areatrabalho->find('list');
		$generos = $this->Aluno->Genero->find('list');
        $turnos = $this->Aluno->Matricula->Turno->find('list');
		$cidadenascimentos = $this->Aluno->CidadeNascimento->find('list');
		$this->set(compact('cursos','planoestudos','users', 'paises', 'cidades', 'provincias', 'documentos', 'areatrabalhos','generos','cidadenascimentos','proveniencianomes','provenienciacidades','turnos'));
	}

	function edit($id = null) {
	        //App::Import('Model','Logmv');
	        //$logmv = new Logmv;
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalido %s', 'flasherror');

			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			
			$imagem = array($this->data['Aluno']['foto']);
			
			$fileOk = $this->uploadFiles('upload',$imagem);
			
			$this->data['Aluno']['foto']=$fileOk['urls'][0];
			
			if ($this->Aluno->save($this->data)) {
				$this->Session->setFlash('Os dados do aluno foram editados com sucesso','flashok');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Erro ao editar dados do aluno. Por favor tente de novo.','flasherror');}
		}
		if (empty($this->data)) {
			$this->data = $this->Aluno->read(null, $id);
			
		}
		$users = $this->Aluno->User->find('list');
		$paises = $this->Aluno->Paise->find('list');
		$cidades = $this->Aluno->Cidade->find('list');
		$provincias = $this->Aluno->Provincia->find('list');
        $provenienciacidades = $this->Aluno->ProvenienciaCidade->find('list');
		$proveniencianomes = $this->Aluno->ProvenienciaProvincia->find('list');
		$documentos = $this->Aluno->Documento->find('list');
		$areatrabalhos = $this->Aluno->Areatrabalho->find('list');
		$generos = $this->Aluno->Genero->find('list');
		$cidadenascimentos = $this->Aluno->CidadeNascimento->find('list');
        $turnos = $this->Aluno->Matricula->Turno->find('list');
		$this->set(compact('users', 'Paises', 'Cidades', 'Provincias', 'Documentos', 'areatrabalhos','generos','cidadenascimentos','proveniencianomes','provenienciacidades','turnos'));
	}




	function beforeRender(){
        parent::beforeRender();	
        $this->set('current_section','estudantes');
    }
		
	function ajax_update_cidade(){
		//var_dump($teste);
		
		$this->layout = 'ajax';
	}
        function pdf_index($id = null){
		    //App::Import('Model','Logmv');
	        //$logmv = new Logmv;
           Configure::write('debug',0); // Otherwise we cannot use this method while developing
             if (empty($this->data)) {
			$this->data = $this->Aluno->read(null, $id);
		}
		$users = $this->Aluno->User->find('list');
		$paises = $this->Aluno->Paise->find('list');
		$cidades = $this->Aluno->Cidade->find('list');
		$provincias = $this->Aluno->Provincia->find('list');
		$documentos = $this->Aluno->Documento->find('list');
		$areatrabalhos = $this->Aluno->Areatrabalho->find('list');
		//$generos = $this->Aluno->Generos->find('list');
		$generos = $this->Aluno->Genero->find('list');
		$this->set(compact('users', 'Paises', 'Cidades', 'Provincias', 'Documentos', 'tg0010areatrabalhos', 'generos'));
		  
		   $teste = array();
		   $teste[] = $id;
		   $teste[] = "Teste2";
           $this->set('teste1',$teste);
		   $this->layout = 'pdf'; //this will use the pdf.ctp layout
           //$this->render('/Alunos/pdf_teste');
		   $this->render();
        }
		
		function pdf_index_alunos(){
		      //App::Import('Model','Logmv');
	          //$logmv = new Logmv;
              Configure::write('debug',0); // Otherwise we cannot use this method while developing
		      $users = $this->Aluno->User->find('list');
			  $aluno = $this->Aluno->find('all');
		      $paises = $this->Aluno->Paise->find('list');
		      $cidades = $this->Aluno->Cidade->find('list');
		      $provincias = $this->Aluno->Provincia->find('list');
		      $documentos = $this->Aluno->Documento->find('list');
		      $areatrabalhos = $this->Aluno->Areatrabalho->find('list');
              $cursos = $this->Aluno->Curso->find('list');
		      $this->set(compact('users', 'Paises', 't0003cursos','Cidades', 'Provincias', 'Documentos', 'tg0010areatrabalhos'));
            $listas = array();
             foreach ($aluno as $m){
                $lista = array();
                $lista[] =$m["Aluno"]["id"];
				$lista[] =$m["Aluno"]["codigo"];
				$lista[] =$m["Aluno"]["name"];
				
				  
				//   $lista[] =$m["Cursos"]["name"];
				//var_dump($m["Curso"]["name"]);
               // $listas[] =$m["Aluno"]["name"];
				//$listas[] =$m["Aluno"]["empresanome"];
				$listas[] =$lista;
                }
           // $this->set('cursos',$this->Curso->find('all'));
           $this->set('lista',$listas);
           $this->layout = 'pdf'; //this will use the pdf.ctp layout
           $this->render();
		 //  $this->render('/Alunos/pdf_teste');
        }

		function exportar_excel(){
			App::import('Vendor', 'Spreadsheet_Excel_Writer', array('file' => 'Spreadsheet/Excel/Writer.php'));
			
			// Creating a workbook
			$workbook = new Spreadsheet_Excel_Writer();
			$format_bold =& $workbook->addFormat();
			$format_bold->setBold();
			
			$format_escola =& $workbook->addFormat();
			$format_escola->setBold();
			$format_escola->setAlign('center');
			
			$format_titulo =& $workbook->addFormat();
			$format_titulo->setBold();
			$format_titulo->setAlign('center');

			// sending HTTP headers
			$workbook->send('alunos_por_curso.xls');
			
			$cursos = $this->Aluno->Curso->find('list');
			
			foreach($cursos as $k=>$v){
				$this->Aluno->Matricula->recursive = 1;
				$alunos = $this->Aluno->Matricula->find('all',array('conditions'=>array('Matricula.curso_id'=>$k),'fields'=>array('Aluno.codigo','Aluno.name','Curso.name','Curso.codigo')));
				// Creating a worksheet
				$worksheet =& $workbook->addWorksheet($alunos[0]['Curso']['codigo']);
				$worksheet->setInputEncoding('utf-8');
				$worksheet->setColumn(0,0,12);
				$worksheet->setColumn(1,1,15);
				$worksheet->setColumn(2,2,45);
				$worksheet->setColumn(3,3,45);
				//$worksheet->insertBitmap(1,1,'/sga/img/logo.bmp');
				// The actual data
				
				$worksheet->write(0, 0, iconv("UTF-8", "ISO-8859-1",'ESCOLA SUPERIOR DE ECONOMIA E GESTÃO'),$format_escola);
				$worksheet->write(1, 0, iconv("UTF-8", "ISO-8859-1",'Lista de Estudantes de '.$alunos[0]['Curso']['name']),$format_titulo);
				$worksheet->setMerge(0, 0, 0, 3);
				$worksheet->setMerge(1, 0, 1, 3);
				$sequencia = 3;
				$worksheet->write($sequencia, 0, 'Sequencia',$format_bold);
				$worksheet->write($sequencia, 1, 'Codigo',$format_bold);
				$worksheet->write($sequencia, 2, 'Nome',$format_bold);
				$worksheet->write($sequencia, 3, 'Curso',$format_bold);
				
				foreach($alunos as $aluno){
					$sequencia++;	
					$worksheet->write($sequencia, 0, $sequencia-3);
					$worksheet->write($sequencia, 1,$aluno['Aluno']['codigo']);
					$worksheet->write($sequencia, 2,iconv("UTF-8", "ISO-8859-1", $aluno['Aluno']['name']));
					$worksheet->write($sequencia, 3, iconv("UTF-8", "ISO-8859-1", $aluno['Curso']['name']));
				}
				
				//var_dump($alunos);
			}
			
			
			
			
			
			$worksheet->write(3, 0, 'Juan Herrera');
			$worksheet->write(3, 1, 32);
			
			// Let's send the file
			$workbook->close();
		}
        
        public function pdf_boletim_matricula($aluno_id){
            $this->set('aluno_id',$aluno_id);
            $this->layout = 'jasper';
        }
}
