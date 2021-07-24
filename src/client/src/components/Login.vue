<template>
  <van-form @submit="onSubmit">
    <van-field
      v-model="account"
      name="账号"
      label="账号"
      placeholder="账号"
      :rules="[{ required: true, message: '请填写账号' }]"
    />
    <van-field
      v-model="password"
      type="password"
      name="密码"
      label="密码"
      placeholder="密码"
      :rules="[{ required: true, message: '请填写密码' }]"
    />
    <div style="margin: 16px;">
      <van-button round block type="info" native-type="submit">提交</van-button>
    </div>
  </van-form>
</template>

<script>
import axios from 'axios'
import { Dialog } from 'vant'

export default {
  name: 'Login',
  data () {
    return {
      account: '',
      password: ''
    }
  },
  methods: {
    onSubmit () {
      let data = {
        'account': this.account,
        'password': this.password
      }
      axios.post('/api/login', data, {
        headers: {
          'Content-Type': 'application/json'
        }
      }).then((response) => {
        if (response.data.code === 0) {
          window.location.href = '/api/success#access_token=' + response.data.data
        } else {
          Dialog.alert({
            title: '提示',
            message: '登陆失败'
          }).then(() => {
            // on close
          })
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
