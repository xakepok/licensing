<?php
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'claim.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {*/
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_licensing&view=claim&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" xmlns="http://www.w3.org/1999/html" class="form-validate">
    <div class="row-fluid">
        <div class="span12 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_LICENSING_MENU_CLAIMS');?></a></li>
                <?php if ($this->id == 0): ?>
                <li><a href="#software" data-toggle="tab"><?php echo JText::_('COM_LICENSING_CLAIMS_VKLADKA_SOFTWARE');?></a></li>
                <?php endif; ?>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <fieldset class="adminform">
                        <div class="control-group form-inline">
                            <?php if ($this->id == 0): ?>
                            <div class="control-label"><?php echo JText::_('COM_LICENSING_FILTER_CLAIMS'); ?></div>
                            <div class="controls">
                                <input type="text" autocomplete="off" placeholder="<?php echo JText::_('COM_LICENSING_FILTER_CLAIMS'); ?>" class="input-xlarge" name="fio" />
                                <input type="button" value="<?php echo JText::_('COM_LICENSING_FILTER_SEARCH');?>" id="filterEmpl" />
                                <input type="button" value="<?php echo JText::_('COM_LICENSING_FILTER_CLEAR');?>" id="filterEmplClr" />
                            </div>
                            <?php endif; ?>
                            <?php foreach ($this->form->getFieldset('names') as $soft) : ?>
                                <div class="control-label"><?php echo $soft->label; ?></div>
                                <div class="controls">
                                    <?php echo $soft->input; ?>
                                    <?php
                                    if ($soft->name == "jform[empl_guid]") : ?>
                                        <input type="button" value="<?php echo JText::_('COM_LICENSING_FILTER_READ');?>" id="readEmpl" />
                                    <?php endif; ?>
                                </div>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </div>
                <?php if ($this->id == 0): ?>
                <div class="tab-pane" id="software">
                    <fieldset class="adminform">
                        <div class="control-group form-inline">
                            <?php foreach ($this->software as $soft) : ?>
                                <div class="control-label"><?php echo $soft->product; ?></div>
                                <div class="controls">
                                    <input type="number" min="0"<?php if ($soft->countAvalible != null) echo "max=\"{$soft->countAvalible}\""; ?> size="3" name="jform[software][<?php echo $soft->id; ?>]" id="jform_software_<?php echo $soft->id; ?>" value="" class="input-xlarge" aria-invalid="false">
                                    &nbsp;<?php if ($soft->countAvalible != null) echo sprintf(JText::_('COM_LICENSING_HINT_AVALIABLE').".", $soft->countAvalible);?>
                                </div>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>