<template>
  <van-form validate-first @submit="onSubmit">
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
      v-model="password_confirm"
      type="password"
      name="确认密码"
      label="确认密码"
      placeholder="确认密码"
      :rules="[{ validator: passwordValidator, required: true, message: '请正确填写确认密码' }]"
    />
    <van-field
      v-model="name"
      name="姓名"
      label="姓名"
      placeholder="姓名"
      :rules="[{ validator: nameValidator, required: true, message: '请填写正确的姓名' }]"
    />
    <van-field
      v-model="id_card"
      name="身份证号"
      label="身份证号"
      placeholder="身份证号"
      :rules="[{ validator: idCardValidator, required: true, message: '请填写正确的身份证号' }]"
    />
    <van-field name="checkbox" label="同意实名认证">
      <template #input>
        <van-checkbox v-model="agreed" shape="square" />
        <van-button round style="height: 26px; margin-left: 16px" @click="gotoAgreement">查看条约</van-button>
      </template>
    </van-field>
    <div style="margin: 16px;">
      <van-button round block type="info" native-type="submit" :disabled="!agreed">提交</van-button>
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
      password_confirm: '',
      name: '',
      id_card: '',
      agreed: false
    }
  },
  methods: {
    gotoAgreement () {
      window.open('/api/agreement', '_blank')
    },
    passwordValidator (val) {
      return this.password === this.password_confirm
    },
    nameValidator (val) {
      return /^[a-zA-Z]+$/.test(val) || /^[\u4e00-\u9fa5]+$/.test(val)
    },
    idCardValidator (val) {
      var format = /^(([1][1-5])|([2][1-3])|([3][1-7])|([4][1-6])|([5][0-4])|([6][1-5])|([7][1])|([8][1-2]))\d{4}(([1][9]\d{2})|([2]\d{3}))(([0][1-9])|([1][0-2]))(([0][1-9])|([1-2][0-9])|([3][0-1]))\d{3}[0-9xX]$/
      // 号码规则校验
      if (!format.test(val)) {
        return false
      }
      // 区位码校验
      // 出生年月日校验   前正则限制起始年份为1900;
      const year = val.substr(6, 4) // 身份证年
      const month = val.substr(10, 2) // 身份证月
      const date = val.substr(12, 2) // 身份证日
      const time = Date.parse(month + '-' + date + '-' + year) // 身份证日期时间戳date
      const nowTime = Date.parse(new Date()) // 当前时间戳
      const dates = (new Date(year, month, 0)).getDate()// 身份证当月天数
      if (time > nowTime || date > dates) {
        return false
      }
      // 校验码判断
      const c = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2] // 系数
      const b = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'] // 校验码对照表
      const idArray = val.split('')
      let sum = 0
      for (var k = 0; k < 17; k++) {
        sum += parseInt(idArray[k]) * parseInt(c[k])
      }
      if (idArray[17].toUpperCase() !== b[sum % 11].toUpperCase()) {
        return false
      }
      return true
    },
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
