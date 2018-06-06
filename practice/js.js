$('#example').popover(options)
$(function () {
  $('[data-toggle="popover"]').popover()
})
$('#example').tooltip(options)
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$('#myModal').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
