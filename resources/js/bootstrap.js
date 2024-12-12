import axios from 'axios';
import 'bootstrap';
import * as Popper from '@popperjs/core';
window.axios = axios;
window.Popper = Popper;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
