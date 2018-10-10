<div class="clearfix">
    <div class="js-stools-container-bar">
        <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
        <div class="btn-wrapper input-append">
            <input type="text" autocomplete="off" name="filter_search" id="filter_search"
                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                   title aria-label="<?php echo JText::_('COM_LICENSING_FILTER_SOFTWARE'); ?>"
                   data-original-title="<?php echo JText::_('COM_LICENSING_FILTER_SOFTWARE'); ?>"
                   placeholder="<?php echo JText::_('COM_LICENSING_FILTER_SOFTWARE'); ?>"
            >
            <button type="submit" class="btn hasTooltip">
                <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
                <span class="icon-search" aria-hidden="true"></span>
            </button>
        </div>
        <div class="js-stools-field-filter">
            <?php echo LicensingHtmlFilters::state($this->state->get('filter.state')); ?>
        </div>
        <div class="js-stools-field-filter">
            <?php echo LicensingHtmlFilters::license($this->state->get('filter.license')); ?>
        </div>
        <div class="js-stools-field-filter">
            <?php echo LicensingHtmlFilters::lictype($this->state->get('filter.licenseType')); ?>
        </div>
        <div class="js-stools-field-filter">
            <?php echo LicensingHtmlFilters::company($this->state->get('filter.company')); ?>
        </div>
        <div class="btn-wrapper">
            <button type="button" class="btn hasTooltip js-stools-btn-clear"
                    onclick="document.getElementById('filter_search').value='';this.form.submit();">
                <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
            </button>
        </div>
    </div>
    <div class="js-stools-container-list hidden-phone hidden-tablet shown" style=""></div>
</div>