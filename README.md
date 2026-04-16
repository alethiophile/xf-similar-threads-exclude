# Exclude from Similar Threads

This addon modifies the SV ElasticSearchEssentials similar-threads widget. It
creates a boolean column on the `xf_thread` table, `qq_exclude_from_similar`.
Threads which have this column set to true will never be featured in the
similar-threads widget. The intended use is for content that need not be
removed, but should not be algorithmically promoted.
