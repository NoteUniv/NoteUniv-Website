export const state = () => ({
  studentId: undefined
})

export const mutations = {
  setUsername(state, studentId) {
    state.studentId = studentId
  }
}
