import { test, expect } from "vitest";
import SimpleTime from "../SimpleTime.vue";
import { mount } from "@vue/test-utils";

test("mounts component with time", () => {
  const wrapper = mount(SimpleTime, {
    props: {
      time: "2022-01-01T00:00:00",
    },
  });

  expect(wrapper.text()).toContain("1 Jan, 2022");
  expect(wrapper.attributes()).toHaveProperty("title", "1 January, 2022 00:00");
  expect(wrapper.attributes()).toHaveProperty("datetime", "2022-01-01T00:00:00");
});
