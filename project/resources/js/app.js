import './bootstrap';
import './echo';
$(document).ajaxError(function (event, jqxhr, settings, thrownError) {
  if (jqxhr.status === 401||jqxhr.status === 403) {
    window.location.href = '/login'; 
  }
});