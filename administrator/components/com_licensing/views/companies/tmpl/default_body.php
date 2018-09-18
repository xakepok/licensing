<?php
defined('_JEXEC') or die;

foreach ($this->items as $i => $company) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.company.' . $item->id);
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $company->id); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $company->state, $i, 'companies.', $canChange); ?>
        </td>
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=company&layout=edit&id='.$company->id);
            echo JHtml::link($link, $company->name);
            ?>
        </td>
        <td>
            <?php
            $link = JHtml::link($company->website, $company->website, array('target' => '_blank'));
            echo $link;
            ?>
        </td>
        <td>
            <?php echo $company->email; ?>
        </td>
        <td>
            <?php echo $company->phone; ?>
        </td>
        <td>
            <?php echo $company->id; ?>
        </td>
    </tr>
<?php endforeach; ?>