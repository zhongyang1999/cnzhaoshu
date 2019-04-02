import filters from './filters'

const importFilter = Vue => {
  Object.keys(filters).forEach(k => Vue.filter(k, filters[k]));
}

export default importFilter