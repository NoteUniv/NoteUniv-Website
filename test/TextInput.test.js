import { mount } from '@vue/test-utils'
import TextInput from '@/components/TextInput'

describe('TextInput', () => {
  test('it should idle by default', () => {
    const wrapper = mount(TextInput)
    expect(wrapper.vm.state).toBe('idle')
  })

  test('it should be successful', (done) => {
    const wrapper = mount(TextInput)
    wrapper.find('input[type="text"]').setValue('00000000')
    wrapper.find('div.cursor-pointer').trigger('click')

    wrapper.vm.$nextTick(() => {
      expect(wrapper.vm.state).toBe('success')
      done()
    })
  })
})
