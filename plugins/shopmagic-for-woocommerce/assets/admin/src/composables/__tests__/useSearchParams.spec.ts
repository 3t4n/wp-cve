import { test, expect } from "vitest";
import { useSearchParams } from "../useSearchParams";

test("converts simple plain object", () => {
  const params = useSearchParams({
    hello: "Hello World",
  });
  expect(params).toBe("hello=Hello%20World");
});

test("converts object with multiple entries", () => {
  const params = useSearchParams({
    hello: "Hello World",
    world: "Ola Mundo",
  });
  expect(params).toBe("hello=Hello%20World&world=Ola%20Mundo");
});

test("converts object with nested entries", () => {
  const params = useSearchParams({
    hello: {
      world: "Hello World",
    },
  });
  expect(params).toBe("hello[world]=Hello%20World");
});

test("converts object with arrays inside", () => {
  const params = useSearchParams({
    hello: ["Hello World", "Ola Mundo"],
  });
  expect(params).toBe("hello[0]=Hello%20World&hello[1]=Ola%20Mundo");
});

test("converts nested object with arrays inside", () => {
  const params = useSearchParams({
    hello: {
      world: ["Hello World", "Ola Mundo"],
    },
  });
  expect(params).toBe("hello[world][0]=Hello%20World&hello[world][1]=Ola%20Mundo");
});
