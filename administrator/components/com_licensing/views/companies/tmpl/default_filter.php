<div>
    <label for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
    <input type="text" autocomplete="off" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_LICENSING_FILTER_COMPANY'); ?>" />
    <select name="filter_state" onchange="this.form.submit()">
        <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
        <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
    </select>
    <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
    <button type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
</div>