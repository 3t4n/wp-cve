import { Box, Heading, HStack, Tag, Text } from "@chakra-ui/react";
import apiFetch from "@wordpress/api-fetch";
import { sprintf, __ } from "@wordpress/i18n";
import React from "react";
import { useQuery } from "react-query";
import { CHANGELOG_TAG_COLORS } from "../constants";
import { ChangelogsMap } from "../types";
import ChangelogSkeleton from "./ChangelogSkeleton";

const Changelog: React.FC = () => {
	const changelogQuery = useQuery(["changelog"], () =>
		apiFetch<ChangelogsMap>({
			path: "magazine-blocks/v1/changelog",
		})
	);

	if (changelogQuery.isLoading) {
		return <ChangelogSkeleton />;
	}

	return (
		<>
			{changelogQuery.data?.map((changelog) => (
				<Box key={changelog.version} mb="7">
					<HStack justify="space-between">
						<Heading as="h4" fontSize="sm" fontWeight="semibold">
							{sprintf(__("Version %s"), changelog.version)}
						</Heading>
						<Text>{changelog.date}</Text>
					</HStack>
					<Box>
						{Object.entries(changelog.changes).map(
							([tag, changes], i) => (
								<Box
									key={`${changelog.version}${tag}${i}`}
									position="relative"
									_after={{
										bgColor:
											CHANGELOG_TAG_COLORS?.[
												tag.trim().toLowerCase()
											]?.bgColor ?? "gray",
										bottom: 0,
										content: '""',
										height: "full",
										left: "12px",
										position: "absolute",
										top: 0,
										width: "2px",
									}}
									mb="10"
									mt="8"
								>
									<Tag
										colorScheme={
											CHANGELOG_TAG_COLORS?.[
												tag.trim().toLowerCase()
											]?.scheme
										}
										position="sticky"
										zIndex={2}
										top="0"
									>
										{tag}
									</Tag>
									<Box pt="10px">
										{changes.map((change, j) => (
											<Text
												key={`${changelog.version}${tag}${i}${j}`}
												pl="10"
												position="relative"
												mb="4"
												_after={{
													bgColor:
														CHANGELOG_TAG_COLORS?.[
															tag
																.trim()
																.toLowerCase()
														]?.bgColor,
													bgPosition: "50%",
													borderRadius: "50%",
													content: '""',
													height: "20px",
													width: "20px",
													position: "absolute",
													top: "50%",
													transform:
														"translateY(-50%)",
													left: "2px",
												}}
												_before={{
													color: CHANGELOG_TAG_COLORS?.[
														tag.trim().toLowerCase()
													]?.color,
													content: '"\\2713"',
													position: "absolute",
													left: "9px",
													top: "50%",
													transform:
														"translateY(-50%)",
													fontSize: "10px",
													fontWeight: "bold",
													zIndex: 1,
												}}
											>
												{change}
											</Text>
										))}
									</Box>
								</Box>
							)
						)}
					</Box>
				</Box>
			))}
		</>
	);
};

export default Changelog;
