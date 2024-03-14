export function isLinkActive(link: string) {
  const { hash } = location;
  if (link === hash) {
    return true;
  }
  return false;
}
