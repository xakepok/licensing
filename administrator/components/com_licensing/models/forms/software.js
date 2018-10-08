'use strict';
function setUnlim()
{
    var unlim = document.getElementById("jform_unlim");
    var val = unlim.options.selectedIndex;
    var cnt = document.querySelector("#jform_count");
    var av = document.querySelector("#jform_countAvalible");
    if (val == 1)
    {
        cnt.disabled = true;
        av.disabled = true;
    }
    if (val == 0)
    {
        cnt.disabled = false;
        av.disabled = false;
    }
}
function setAvaliable()
{
    var cnt = document.querySelector("#jform_count");
    var av = document.querySelector("#jform_countAvalible");
    av.value = cnt.value;
    av.select();
}
window.onload = function () {
    var unlim = document.getElementById("jform_unlim");
    var cnt = document.querySelector("#jform_count");
    setUnlim();
    unlim.onchange = setUnlim;
    var url_string = window.location.href; // www.test.com?filename=test
    var url = new URL(url_string);
    var id = url.searchParams.get("id");
    if (id == undefined)
    {
        cnt.onchange = setAvaliable;
    }
};