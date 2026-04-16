# Exclude from Similar Threads

This addon modifies the SV ElasticSearchEssentials similar-threads widget. It
creates a boolean column on the `xf_thread` table, `qq_exclude_from_similar`.
Threads which have this column set to true will never be featured in the
similar-threads widget. The intended use is for content that need not be
removed, but should not be algorithmically promoted.

## Usage instructions

The addon creates a new field per-thread. This can be edited by moderators via
the "edit this thread" popup (requires the "manage any thread" permission). It
is a boolean yes/no; if the field is "yes", then the thread will never be
featured in the "similar threads" widget.

Note that, if many threads are excluded, the widget may show fewer threads than
configured. (Specifically, the addon bumps the search window by 5; if five or
more threads in a given thread's results are excluded, then the remaining number
will be smaller than the originally configured count.)
