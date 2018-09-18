<div>
    <label for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
    <input type="text" autocomplete="off" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_LICENSING_FILTER_SOFTWARE'); ?>" />
    <?php echo LicensingHtmlFilters::claim($this->state->get('filter.claim')); ?>
    <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
    <button type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
</div>
