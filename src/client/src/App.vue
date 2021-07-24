<template>
  <div id="app">
    <van-tabs>
      <van-tab title="登 陆">
        <Login></Login>
      </van-tab>
      <van-tab title="注 册">
        <Register></Register>
      </van-tab>
    </van-tabs>
  </div>
</template>

<script>
import Login from './components/Login'
import Register from './components/Register'

export default {
  name: 'App',
  components: {
    Login,
    Register
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
