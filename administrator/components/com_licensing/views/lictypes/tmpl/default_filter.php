<div>
    <label for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
    <input type="text" autocomplete="off" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_LICENSING_FILTER_LICTYPE'); ?>" />
    <?php echo LicensingHtmlFilters::state($this->state->get('filter.state')); ?>
    <?php echo LicensingHtmlFilters::company($this->state->get('filter.company')); ?>
    <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
    <button type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
</div>
