'use strict';
function setUnlim()
{
    var unlim = document.getElementById("jform_unlim");
    var val = unlim.options.selectedIndex;
    var cnt = document.querySelector("#jform_count");
    var av = document.querySelector("#jform_countAvalible");
    var reserv = document.querySelector("#jform_countReserv");
    if (val == 1)
    {
        cnt.disabled = true;
        av.disabled = true;
        reserv.disabled = true;
    }
    if (val == 0)
    {
        cnt.disabled = false;
        av.disabled = false;
        reserv.disabled = false;
    }
}

window.onload = function () {
    var unlim = document.getElementById("jform_unlim");
    setUnlim();
    unlim.onchange = setUnlim;
};