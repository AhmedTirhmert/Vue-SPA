import axios from 'axios'
var local = 'http://localhost:8000/api'
//let Api = 'http://www.apilarapro.ml/api'
export default axios.create({
    baseURL:local
})