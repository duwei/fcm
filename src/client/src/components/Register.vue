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
    <van-field
      v-model="name"
      name="姓名"
      label="姓名"
      placeholder="姓名"
      :rules="[{ required: true, message: '请填写姓名' }]"
    />
    <van-field
      v-model="id_card"
      name="身份证号"
      label="身份证号"
      placeholder="身份证号"
      :rules="[{ required: true, message: '请填写身份证号' }]"
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
  name: 'Register',
  data () {
    return {
      account: '',
      password: '',
      name: '',
      id_card: ''
    }
  },
  methods: {
    onSubmit (values) {
      let data = {
        'account': this.account,
        'password': this.password,
        'name': this.name,
        'id_card': this.id_card
      }
      axios.post('/api/register', data, {
        headers: {
          'Content-Type': 'application/json'
        }
      }).then((response) => {
        if (response.data.code === 0) {
          if (response.data.data.title !== '') {
            Dialog.alert({
              title: response.data.data.title,
              message: response.data.data.content
            }).then(() => {
              window.location.href = '/api/success#access_token=' + response.data.data.access_token
            })
          } else {
            window.location.href = '/api/success#access_token=' + response.data.data.access_token
          }
        } else {
          Dialog.alert({
            title: '提示',
            message: response.data.msg
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
