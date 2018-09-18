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
function setData()
{
    var fio = document.getElementById("jform_empl_guid");
    var phone = fio.options[fio.selectedIndex].getAttribute('data-phone');
    var email = fio.options[fio.selectedIndex].getAttribute('data-email');
    var podrazd = fio.options[fio.selectedIndex].getAttribute('data-podrazd');
    var uid = fio.options[fio.selectedIndex].getAttribute('data-login');
    document.querySelector("#jform_phone").value = phone;
    document.querySelector("#jform_email").value = email;
    document.querySelector("#jform_structure").value = podrazd;
    document.querySelector("#jform_uid").value = uid;
    document.querySelector("#jform_empl_fio").value = fio.options[fio.selectedIndex].getAttribute('data-fio');
}
window.onload = function () {
    var field = document.getElementById("filterEmpl");
    var clr = document.getElementById("filterEmplClr");
    var fio = document.getElementById("jform_empl_guid");
    var readEmpl = document.getElementById("readEmpl");
    field.addEventListener('click', employerFilter, false);
    clr.addEventListener('click', filterClr, false);
    readEmpl.addEventListener('click', setData, false);
    fio.onchange = setData;
};