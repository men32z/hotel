import axios from 'axios';
import Swal from 'sweetalert2';
import * as helpers from '@/helpers.js'

const state = {
  s_token :'',
  token: helpers.getCookie('user-token') || '',
  session:{},
};

const getters =  {
  isAuthenticated: state => !!state.token || (!!state.session && !!state.session.token) || false,
  getToken:state => state.s_token,
};

const actions = {
  async authRequest({commit}, credentials){
    try {
      const response = await axios.post(process.env.VUE_APP_BE+'/api/auth/login', credentials);
      //console.log(response);
      if(response.data.logged){
          commit('sessionAccepted', response.data.logged);
          commit('setToken', response.data.logged.access_token);
          commit('setErrors', []);
      } else if(response.data.errors) {
        commit('setErrors', response.data.errors);
        throw new Error("some errors in form");
      } else {
        throw new Error("something wrong")
      }
      return "logged";
    } catch (e) {
      Swal.fire(
        'Error!',
        e.message,
        'error'
      );
      return "error";
    }
  },
  async authSignUp({commit}, credentials){
    try {
      const response = await axios.post(process.env.VUE_APP_BE+'/api/auth/signup', credentials);
      if(response.data.registered){
          Swal.fire(
            'Good job!',
            'Price updated!',
            'success'
          );
          commit('setErrors', []);
      } else if(response.data.errors) {
        commit('setErrors', response.data.errors);
        throw new Error("some errors in form");
      } else {
        throw new Error("something wrong")
      }
      return "registered";
    } catch (e) {
      //console.log(e);
      Swal.fire(
        'Error!',
        e.message,
        'error'
      );
      return "error";
    }
  },
  async authLogOut({commit, getters}){
     var headers = { headers: {"Authorization" : `Bearer ${getters.getToken}`} }
    try {
      const response = await axios.get(process.env.VUE_APP_BE+'/api/auth/logout', headers);
      //console.log(response);
      if(response.data.great){
          commit('cleanSession', response.data);
          commit('setToken', '');
          //console.log(getters.getToken);
      } else {
        throw new Error("something wrong")
      }
      return "great";
    } catch (e) {
      Swal.fire(
        'Error!',
        e.message,
        'error'
      );
      return "error";
    }
  },
  updateAuthStatus({commit}){
    if(helpers.getCookie('user-token') !='') commit('setToken', helpers.getCookie('user-token'));
  },
};

const mutations = {
  sessionAccepted: (state, session) => {
    if(session.access_token){
      //console.log(session);
      state.session = session;
      helpers.setCookie('user-token', session.access_token, 7);
    }
  },
  cleanSession:(state, data) => {
    helpers.eraseCookie('user-token');
    state.session = {};
  },
  setToken:(state, token) =>state.s_token = token,
};

export default {
  state,
  getters,
  actions,
  mutations
}