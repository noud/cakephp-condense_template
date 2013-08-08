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
<?php echo "<?php echo \$this->Html->script('jquery'); ?>\n"; ?>

<?php echo "<?php\n"; ?>
<?php echo "if (empty(\${$pluralVar})) {\n"; ?>
	<?php echo "\t\$data = \$this->requestAction(array('controller' => '{$pluralHumanName}', 'action' => 'listing', 'foreignKeys' => \$foreignKeys, 'foreignIds' => \$foreignIds));\n"; ?>
	<?php echo "\t\${$pluralVar} = \$data['{$pluralVar}'];\n"; ?>
	<?php echo "\t\$this->Paginator->params['paging'] = \$data['paging'];\n"; ?>
}
<?php echo "\$this->Paginator->options(array(\n"; ?>
	<?php echo "\t'update' => '#'.\$updateId,\n"; ?>
	<?php echo "\t'evalScripts' => true,\n"; ?>
	<?php echo "\t'before' => \$this->Js->get('#spinner')->effect('fadeIn', array('buffer' => false)),\n"; ?>
	<?php echo "\t'complete' => \$this->Js->get('#spinner')->effect('fadeOut', array('buffer' => false)),\n"; ?>
	<?php echo "\t'url' => array('controller' => '{$pluralHumanName}', 'action' => 'listing', 'updateId' => \$updateId, 'foreignKeys' => \$foreignKeys, 'foreignIds' => \$foreignIds),\n"; ?>
	<?php echo "\t'model' => '{$modelClass}',"; ?>
<?php echo "));\n"; ?>
<?php echo "?>\n"; ?>

<?php echo "<div id=\"<?php echo \$updateId ?>\">\n"; ?>

	<?php
		$skipFields = array();
			$meta = Configure::read('meta');
		foreach ($fields as $field) {
			$camelizedField = Inflector::camelize($field);
			if (array_key_exists($singularHumanName, $meta) && array_key_exists($camelizedField, $meta[$singularHumanName]) && array_key_exists('suppresIndex', $meta[$singularHumanName][$camelizedField]) ) {
				$skipFields[] = $field;
			}
		}
	?>
<div class="related">
	<h3><?php echo "<?php echo __('Related %s', '{$pluralHumanName}'); ?>"; ?></h3>
	<table cellpadding="0" cellspacing="0">
	<tr>
	<?php foreach ($fields as $field): ?>
	<?php /** skip some fields **/
	if (in_array($field, $skipFields) || (!empty($schema[$field]['key']) && $schema[$field]['key'] == 'primary')) {
		continue;
	} ?>
	<?php if ($schema[$field]['type'] != 'text') { ?>
		<th><?php echo "<?php echo \$this->Paginator->sort('{$field}', null, array('model' => '{$modelClass}')); ?>"; ?></th>
		<?php } ?>
	<?php endforeach; ?>
		<th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
	</tr>
	<?php
	echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
	echo "\t<tr>\n";
		foreach ($fields as $field) {
	/** skip some fields **/
	if (in_array($field, $skipFields) || (!empty($schema[$field]['key']) && $schema[$field]['key'] == 'primary')) {
		continue;
	}
			if ($schema[$field]['type'] != 'text') {
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
			$meta = Configure::read('meta');
			if (array_key_exists($singularHumanName, $meta) && array_key_exists($field, $meta[$singularHumanName]) && ($meta[$singularHumanName][$field]['type'] == 'file') ) {
				echo "\t\t<td><?php \$content = \${$singularVar}['{$modelClass}']['{$field}']; \$label = substr(\$content,strlen(\$content)-3); echo \$this->Html->link(\$label, DS . 'files' . DS . \${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
			} elseif (array_key_exists($singularHumanName, $meta) && array_key_exists($field, $meta[$singularHumanName]) && ($meta[$singularHumanName][$field]['type'] == 'url') ) {
				echo "\t\t<td><?php if (strlen(\${$singularVar}['{$modelClass}']['{$field}'])) {echo \$this->Html->link(\${$singularVar}['{$modelClass}']['{$field}'], \${$singularVar}['{$modelClass}']['{$field}']);} ?>&nbsp;</td>\n";
			} else {
				echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
			}
		}
			}	# text
		}

		echo "\t\t<td class=\"actions\">\n";
		echo "\t\t\t<?php echo \$this->Html->link(__('View'), array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		echo "\t\t\t<?php echo \$this->Html->link(__('Edit'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		echo "\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), null, __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		echo "\t\t</td>\n";
	echo "\t</tr>\n";

	echo "<?php endforeach; ?>\n";
	?>
	</table>
	<p>
	<?php echo "<?php
	echo \$this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'),
	'model' => '{$modelClass}',
	));
	?>"; ?>
	</p>
	<div class="paging">
	<?php
		echo "<?php\n";
		echo "\t\techo \$this->Paginator->prev('< ' . __('previous'), array('model' => '{$modelClass}'), null, array('class' => 'prev disabled'));\n";
		echo "\t\techo \$this->Paginator->numbers(array('separator' => '', 'model' => '{$modelClass}'));\n";
		echo "\t\techo \$this->Paginator->next(__('next') . ' >', array('model' => '{$modelClass}'), null, array('class' => 'next disabled'));\n";
		echo "\t?>\n";
	?>
	</div>
</div>

	<div class="actions">
		<ul>
			<li><?php echo "<?php echo \$this->Html->link(__('New %s', '{$modelClass}'), array('controller' => '{$pluralHumanName}', 'action' => 'add')); ?>\n"; ?></li>
		</ul>
	</div>

</div>
<?php echo "<?php echo \$this->Js->writeBuffer(); ?>"; ?>
