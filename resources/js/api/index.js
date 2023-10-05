import client from '../helpers/client'

const urlDetailMatch = '/api/match/detail/'
const urlGetUserInfo = '/auth/user/info'
const urlSaveLog = '/api/save_logs'
const urlPayLink = '/api/paylink'
const urlPayLinkUid = '/api/paylink_uid'
const urlCharge = '/api/charge'
const urlHandleLog = '/api/handle_log'
const urlPayLinkBytePay = '/api/paylink-bytepay'

export default {
    getDetailMatch(matchId, payload) {
        return client.post(urlDetailMatch + matchId, payload)
    },
    getUserInfo() {
        return client.get(urlGetUserInfo)
    },
    saveLog(payload) {
        return client.post(urlSaveLog, payload)
    },
    getPayLink(payload) {
        return client.post(urlPayLink, payload)
    },
    getPayLinkUid(params) {
        return client.get(urlPayLinkUid, {params: params})
    },
    charge(payload) {
        return client.post(urlCharge, payload)
    },
    handleLog(payload) {
        return client.post(urlHandleLog, payload)
    },
    getPayLinkBytePay(payload) {
        return client.post(urlPayLinkBytePay, payload)
    },
}
