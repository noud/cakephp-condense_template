<?php
/**
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @author Noud deBROUWER <noud4@home.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>

		public $components = array('Search.Prg', 'RequestHandler');

	public $helpers = array('Js');

	public $presetVars = true;

	public $paginate = array('<?php echo $currentModelName; ?>' => array('limit' => 9,),);

	public function beforeFilter() {
<?php
	foreach ($modelObj->{'belongsTo'} as $associationName => $relation):
		$otherTableizedName = strtolower($this->_pluralName($associationName));
		echo "\t\t\$this->set('".$otherTableizedName."', \$this->".$currentModelName."->".$otherTableizedName.");\n";
	endforeach;
?>
		parent::beforeFilter();
	}

/**
 * <?php echo $admin ?>index method
 *
 * @return void
 */
	public function <?php echo $admin ?>index() {
		$this-><?php echo $currentModelName ?>->recursive = 0;
		$this->Prg->commonProcess();
		$this->paginate['conditions'] = $this-><?php echo $currentModelName ?>->parseCriteria($this->Prg->parsedParams());
<?php foreach ($modelObj->{'belongsTo'} as $associationName => $relation): ?>
		$this->paginate['conditions'] += $this-><?php echo $currentModelName ?>-><?php echo $associationName ?>->parseCriteria($this->Prg->parsedParams());
<?php endforeach; ?>
		$this->set('<?php echo $pluralName ?>', $this->paginate());
	}

/**
 * <?php echo $admin ?>view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function <?php echo $admin ?>view($id = null) {
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		$options = array('conditions' => array('<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id));
		$this->set('<?php echo $singularName; ?>', $this-><?php echo $currentModelName; ?>->find('first', $options));
	}

<?php $compact = array(); ?>
/**
 * <?php echo $admin ?>add method
 *
 * @return void
 */
	public function <?php echo $admin ?>add() {
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(__('<?php echo ucfirst(strtolower($currentModelName)); ?> saved.'), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'));
<?php endif; ?>
			}
		}
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
/**
 * <?php echo $admin ?>edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function <?php echo $admin; ?>edit($id = null) {
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(__('The <?php echo strtolower($singularHumanName); ?> has been saved.'), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'));
<?php endif; ?>
			}
		} else {
			$options = array('conditions' => array('<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id));
			$this->request->data = $this-><?php echo $currentModelName; ?>->find('first', $options);
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

/**
 * <?php echo $admin ?>delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function <?php echo $admin; ?>delete($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this-><?php echo $currentModelName; ?>->delete()) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'));
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'), array('action' => 'index'));
<?php endif; ?>
		}
<?php if ($wannaUseSession): ?>
		$this->Session->setFlash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'));
<?php else: ?>
		$this->flash(__('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'), array('action' => 'index'));
<?php endif; ?>
		$this->redirect(array('action' => 'index'));
	}

/**
 * <?php echo $admin ?>listing method
 *
 * @return void
 */
	public function <?php echo $admin; ?>listing() {
		// avoid problems when requestAction calls this function
		$this->autoRender = false;

		// tell the view which control id will be updated on next post
		if (!empty($this->passedArgs['updateId'])) {
			$this->set('updateId', $this->passedArgs['updateId']);
		}

		$this-><?php echo $currentModelName; ?>->recursive = 0;
//		$<?php echo $pluralName ?> = $this->paginate();
//		$this->set('<?php echo $pluralName ?>', $<?php echo $pluralName ?>);
		$conditions = array();
		$counter = 0;
		if(isset($this->params['named']['foreignKeys'])) {
			foreach($this->params['named']['foreignKeys'] as $foreignKey) {
				array_push($conditions,array("<?php echo $currentModelName; ?>.$foreignKey" => $this->params['named']['foreignIds'][$counter]));
				$counter++;
			}
		}
       		$<?php echo $pluralName ?> = $this->paginate('<?php echo $currentModelName; ?>', $conditions);
		$this->set('<?php echo $pluralName ?>', $<?php echo $pluralName ?>);
		if(isset($this->params['named']['foreignKeys'])) {
			$this->set('foreignKeys', $this->params['named']['foreignKeys']);
		} else {
			$this->set('foreignKeys', array());
		}
		if(isset($this->params['named']['foreignIds'])) {
			$this->set('foreignIds', $this->params['named']['foreignIds']);
		} else {
			$this->set('foreignIds', array());
		}

		//$paging = $this->params['paging'];

		// if this is an ajax request, render the element
		if ($this->RequestHandler->isAjax()) {
			$this->render('/Elements/<?php echo Inflector::humanize($pluralName) ?>/list');
			return;
		}

		if (isset($this->params['requested'])) {
			// set the correct params on the paging helper of the requesting controller (not this controller)
			return array('<?php echo $pluralName ?>' => $<?php echo $pluralName ?>, 'paging' => $this->params['paging']);
		} else {
			$this->render('/Elements/<?php echo Inflector::humanize($pluralName) ?>/list');
		}
	}
