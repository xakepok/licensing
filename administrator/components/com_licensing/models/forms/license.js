'use strict';
function setUnlim()
{
    var unlim = document.getElementById("jform_unlim");
    var val = unlim.options.selectedIndex;
    var de = document.querySelector("#jform_dateExpires");
    if (val == 1)
    {
        //de.disabled = true;
        de.value = '0000-00-00 00:00:00';
    }
    if (val == 0)
    {
        //de.disabled = false;
    }
}

window.onload = function () {
    var unlim = document.getElementById("jform_unlim");
    setUnlim();
    unlim.onchange = setUnlim;
};