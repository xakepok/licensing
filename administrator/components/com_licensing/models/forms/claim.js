'use strict';
function employerFilter()
{
    var fio = document.querySelector("input[name='fio']").value;
    location.href = '/administrator/index.php?option=com_licensing&view=claim&layout=edit&fio=' + fio;
}
function filterClr()
{
    location.href = '/administrator/index.php?option=com_licensing&view=claim&layout=edit';
}
function setStruct() {
    var guid = document.querySelector("#jform_empl_guid").value;
    location.href = '/administrator/index.php?option=com_licensing&view=claim&layout=edit&guid=' + guid;
}
function setData()
{
    var fio = document.getElementById("jform_empl_guid");
    var phone = fio.options[fio.selectedIndex].getAttribute('data-phone');
    var email = fio.options[fio.selectedIndex].getAttribute('data-email');
    var uid = fio.options[fio.selectedIndex].getAttribute('data-login');
    document.querySelector("#jform_phone").value = phone;
    document.querySelector("#jform_email").value = email;
    document.querySelector("#jform_uid").value = uid;
    document.querySelector("#jform_empl_fio").value = fio.options[fio.selectedIndex].getAttribute('data-fio');
}
window.onload = function () {
    var url_string = window.location.href; // www.test.com?filename=test
    var url = new URL(url_string);
    var claim = url.searchParams.get("id");
    if (claim == undefined) setData();
    var field = document.getElementById("filterEmpl");
    var clr = document.getElementById("filterEmplClr");
    var fio = document.getElementById("jform_empl_guid");
    var readStruct = document.getElementById("readStruct");
    field.addEventListener('click', employerFilter, false);
    clr.addEventListener('click', filterClr, false);
    readStruct.addEventListener('click', setStruct, false);
    fio.onchange = setData;
};