// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import 'vant/lib/index.css'
import Vue from 'vue'
import App from './App'
import Vant from 'vant'

Vue.use(Vant)

Vue.config.productionTip = false
/* git test */
/* eslint-disable no-new */
new Vue({
  el: '#app',
  components: { App },
  template: '<App/>'
})
